<?php

namespace App\Services;

use App\Models\Poster;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Image;

class ImageProcessor
{
    protected ImageManager $manager;
    
    public function __construct()
    {
        $this->manager = new ImageManager(new GdDriver());
    }
    
    /**
     * Process the poster image with overlay and text
     *
     * @param Poster $poster
     * @return bool
     */
    public function processPoster(Poster $poster)
    {
        try {
            // Tingkatkan batas waktu eksekusi
            set_time_limit(300); // 5 menit
            
            Log::info('Memproses poster ID: ' . $poster->id . ' dengan skala: ' . $poster->scale_gambar . ', posX: ' . $poster->pos_x . ', posY: ' . $poster->pos_y);
            
            // Pastikan path file gambar ada
            $gambarPath = Storage::disk('public')->path($poster->gambar);
            $framePath = Storage::disk('public')->path($poster->frame);
            
            if (!file_exists($gambarPath) || !file_exists($framePath)) {
                Log::error('File gambar atau frame tidak ditemukan');
                return false;
            }
            
            // Canvas akan dibuat setelah pengolahan resizedMain
            
            // Load gambar utama
            $extension = pathinfo($gambarPath, PATHINFO_EXTENSION);
            $mainImage = null;
            
            if (strtolower($extension) === 'jpg' || strtolower($extension) === 'jpeg') {
                $mainImage = imagecreatefromjpeg($gambarPath);
            } elseif (strtolower($extension) === 'png') {
                $mainImage = imagecreatefrompng($gambarPath);
            }
            
            if (!$mainImage) {
                Log::error('Gagal membaca gambar utama');
                return false;
            }
            
            // Buat canvas langsung dengan ukuran 2000x2000
            $canvas = imagecreatetruecolor(2000, 2000);
            
            // Gunakan gambar asli, sesuaikan ukuran untuk mengisi penuh canvas (tidak menggunakan scale)
            $orig_width = imagesx($mainImage);
            $orig_height = imagesy($mainImage);
            
            // Hitung rasio aspek untuk mempertahankan proporsionalitas
            $ratio_orig = $orig_width / $orig_height;
            
            // Target width dan height minimal 2000x2000 (memenuhi canvas)
            $target_width = 2000;
            $target_height = 2000;
            
            // Hitung dimensi agar gambar minimal memenuhi canvas
            if ($target_width / $target_height > $ratio_orig) {
                // Jika target lebih lebar, atur width dan sesuaikan height
                $new_width = $target_width;
                $new_height = $target_width / $ratio_orig;
            } else {
                // Jika target lebih tinggi, atur height dan sesuaikan width
                $new_height = $target_height;
                $new_width = $target_height * $ratio_orig;
            }
            
            // Sekarang terapkan skala dan posisi dari pengaturan user
            $scale = $poster->scale_gambar ?? 1.0;
            $new_width *= $scale;
            $new_height *= $scale;
            
            // Posisi gambar (dari tengah canvas)
            $posX = ($poster->pos_x ?? 0);
            $posY = ($poster->pos_y ?? 0);
            
            // Posisi x,y dari tengah canvas
            $centerX = (2000 - $new_width) / 2 + $posX;
            $centerY = (2000 - $new_height) / 2 + $posY;
            
            // Pastikan dimensi dan posisi valid
            if ($new_width <= 0) $new_width = 1;
            if ($new_height <= 0) $new_height = 1;
            
            // Resize dan posisikan gambar langsung ke canvas
            imagecopyresampled(
                $canvas, 
                $mainImage, 
                $centerX, $centerY, 
                0, 0, 
                $new_width, $new_height, 
                $orig_width, $orig_height
            );
            imagedestroy($mainImage);
            
            // Load frame overlay
            $frame = imagecreatefrompng($framePath);
            if (!$frame) {
                Log::error('Gagal membaca frame');
                imagedestroy($canvas);
                return false;
            }
            
            // Resize frame ke 2000x2000
            $resizedFrame = imagecreatetruecolor(2000, 2000);
            imagealphablending($resizedFrame, false);
            imagesavealpha($resizedFrame, true);
            imagecopyresampled($resizedFrame, $frame, 0, 0, 0, 0, 2000, 2000, imagesx($frame), imagesy($frame));
            imagedestroy($frame);
            
            // Overlay frame ke canvas
            imagealphablending($canvas, true);
            imagecopy($canvas, $resizedFrame, 0, 0, 0, 0, 2000, 2000);
            imagedestroy($resizedFrame);
            
            // Tambahkan teks
            $white = imagecolorallocate($canvas, 255, 255, 255);
            
            // Path font Arial Black dan Calibri Bold
            $arialBlackPath = realpath(public_path('fonts/new/ariblk.ttf'));
            $calibriBoldPath = realpath(public_path('fonts/new/calibri-bold.ttf'));
            
            // Log info tambahan untuk debugging
            Log::info('Path font Arial Black: ' . $arialBlackPath);
            Log::info('Path font Calibri Bold: ' . $calibriBoldPath);
            
            // Coba juga path alternatif jika font tidak ditemukan
            if (!$arialBlackPath) {
                $arialBlackPath = realpath(base_path('fonts/new/ariblk.ttf'));
                Log::info('Mencoba path alternatif untuk Arial Black: ' . $arialBlackPath);
            }
            
            if (!$calibriBoldPath) {
                $calibriBoldPath = realpath(base_path('fonts/new/calibri-bold.ttf'));
                Log::info('Mencoba path alternatif untuk Calibri Bold: ' . $calibriBoldPath);
            }
            
            // Verifikasi file font ada
            if (!file_exists($arialBlackPath)) {
                Log::error('Font Arial Black tidak ditemukan di: ' . $arialBlackPath);
            }
            
            if (!file_exists($calibriBoldPath)) {
                Log::error('Font Calibri Bold tidak ditemukan di: ' . $calibriBoldPath);
            }
            
            // Ukuran font yang lebih besar
            $fontSizeJudul = 75; // Ukuran font untuk judul (dikurangi dari 1000)
            $fontSizeNarasi = 45; // Ukuran font untuk narasi (dikurangi dari 800)
            
            // Teks yang akan ditampilkan
            $judul = $poster->judul;
            $narasi = $this->limitText($poster->narasi);
            
            // Hitung lebar teks untuk pemusatan
            $bbox = imagettfbbox($fontSizeJudul, 0, $arialBlackPath, $judul);
            $judulWidth = $bbox[2] - $bbox[0];
            $judulX = (2000 - $judulWidth) / 2;
            
            $bbox = imagettfbbox($fontSizeNarasi, 0, $calibriBoldPath, $narasi);
            $narasiWidth = $bbox[2] - $bbox[0];
            $narasiX = (2000 - $narasiWidth) / 2;
            
            // Posisi Y untuk judul dan narasi
            $judulY = 1600;  // Menyesuaikan dari 1600 ke 1700
            $narasiY = 1850; // Menyesuaikan dari 1800 ke 1850
            
            // Coba gunakan font TTF
            $judulSuccess = false;
            $narasiSuccess = false;
            
            try {
                // PENDEKATAN PERTAMA: MENGGUNAKAN GD NATIVE
                // Tambahkan judul dengan Arial Black
                if (file_exists($arialBlackPath)) {
                    $result = imagettftext($canvas, $fontSizeJudul, 0, $judulX, $judulY, $white, $arialBlackPath, $judul);
                    if ($result !== false) {
                        $judulSuccess = true;
                        Log::info('Berhasil menggunakan font TTF untuk judul: ' . $arialBlackPath);
                    } else {
                        Log::error('Gagal menggunakan font TTF untuk judul meskipun file ada');
                    }
                }
                
                // Tambahkan narasi dengan Calibri Bold dengan word wrap
                if (file_exists($calibriBoldPath)) {
                    // Gunakan wordWrapText untuk membagi teks menjadi baris-baris
                    $lines = $this->wordWrapText($narasi, $fontSizeNarasi, $calibriBoldPath);
                    
                    $lineHeight = $fontSizeNarasi * 1.1; // Mengurangi tinggi baris dari 1.2x menjadi 1.1x
                    $startY = $narasiY - (count($lines) - 1) * $lineHeight; // Mulai dari atas agar tetap berakhir di posisi $narasiY
                    
                    $narasiSuccess = true;
                    
                    foreach ($lines as $index => $line) {
                        // Hitung posisi X untuk setiap baris agar tetap di tengah
                        $bbox = imagettfbbox($fontSizeNarasi, 0, $calibriBoldPath, $line);
                        $lineWidth = $bbox[2] - $bbox[0];
                        $lineX = (2000 - $lineWidth) / 2;
                        
                        $lineY = $startY + ($index * $lineHeight);
                        
                        $result = imagettftext($canvas, $fontSizeNarasi, 0, $lineX, $lineY, $white, $calibriBoldPath, $line);
                        
                        if ($result === false) {
                            $narasiSuccess = false;
                            Log::error('Gagal menggunakan font TTF untuk narasi baris ' . ($index + 1));
                            break;
                        }
                    }
                    
                    if ($narasiSuccess) {
                        Log::info('Berhasil menggunakan font TTF untuk narasi: ' . $calibriBoldPath);
                    } else {
                        Log::error('Gagal menggunakan font TTF untuk narasi meskipun file ada');
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error saat menambahkan teks TTF dengan GD native: ' . $e->getMessage());
            }
            
            // Jika TTF gagal dengan GD native, coba dengan Intervention\Image
            if (!$judulSuccess || !$narasiSuccess) {
                try {
                    Log::info('Mencoba pendekatan dengan Intervention\Image untuk menambahkan teks');
                    
                    // Buat objek Image dari canvas GD
                    $img = $this->manager->read(
                        function () use ($canvas) {
                            ob_start();
                            imagejpeg($canvas);
                            $buffer = ob_get_contents();
                            ob_end_clean();
                            return $buffer;
                        }
                    );
                    
                    // Tambahkan judul jika belum berhasil
                    if (!$judulSuccess && file_exists($arialBlackPath)) {
                        $img->text($judul, 1000, $judulY, function ($font) use ($arialBlackPath, $fontSizeJudul) {
                            $font->filename($arialBlackPath);
                            $font->size($fontSizeJudul);
                            $font->color('#ffffff');
                            $font->align('center');
                        });
                        $judulSuccess = true;
                        Log::info('Berhasil menggunakan Intervention\Image untuk judul');
                    }
                    
                    // Tambahkan narasi jika belum berhasil
                    if (!$narasiSuccess && file_exists($calibriBoldPath)) {
                        // Menggunakan wordWrapText untuk teks narasi
                        $lines = $this->wordWrapText($narasi, $fontSizeNarasi, $calibriBoldPath);
                        
                        $lineHeight = $fontSizeNarasi * 1.1; // Mengurangi tinggi baris dari 1.2x menjadi 1.1x
                        $startY = $narasiY - (count($lines) - 1) * $lineHeight; // Mulai dari atas agar tetap berakhir di posisi $narasiY
                        
                        foreach ($lines as $index => $line) {
                            $lineY = $startY + ($index * $lineHeight);
                            
                            $img->text($line, 1000, $lineY, function ($font) use ($calibriBoldPath, $fontSizeNarasi) {
                                $font->filename($calibriBoldPath);
                                $font->size($fontSizeNarasi);
                                $font->color('#ffffff');
                                $font->align('center');
                            });
                        }
                        
                        $narasiSuccess = true;
                        Log::info('Berhasil menggunakan Intervention\Image untuk narasi');
                    }
                    
                    // Simpan kembali ke canvas
                    imagedestroy($canvas);
                    $canvas = imagecreatefromstring($img->encode('jpg')->getEncoded());
                    
                } catch (\Exception $e) {
                    Log::error('Error saat menambahkan teks dengan Intervention\Image: ' . $e->getMessage());
                }
            }
            
            // Jika TTF gagal dengan kedua pendekatan, gunakan metode createLargeTextImage sebagai fallback terakhir
            if (!$judulSuccess) {
                Log::warning('Menggunakan fallback untuk judul karena semua metode TTF gagal');
                $this->createLargeTextImage($canvas, $judul, 1700, $white, $white, 'judul');
            }
            
            if (!$narasiSuccess) {
                Log::warning('Menggunakan fallback untuk narasi karena semua metode TTF gagal');
                $this->createLargeTextImage($canvas, $narasi, 1800, $white, $white, 'narasi');
            }
            
            // Generate nama file
            $filename = 'posters/' . time() . '_' . uniqid() . '.jpg';
            
            // Simpan ke storage/app/public
            $storagePath = Storage::disk('public')->path($filename);
            
            // Pastikan direktori ada
            $directory = dirname($storagePath);
            if (!is_dir($directory)) {
                if (!mkdir($directory, 0777, true)) {
                    Log::error('Gagal membuat direktori storage: ' . $directory);
                    imagedestroy($canvas);
                    return false;
                }
            }
            
            // Simpan hasil ke storage
            $success = imagejpeg($canvas, $storagePath, 95);
            
            // Simpan juga ke public/storage untuk memastikan akses langsung
            $publicPath = public_path('storage/' . $filename);
            $publicDir = dirname($publicPath);
            
            if (!is_dir($publicDir)) {
                if (!mkdir($publicDir, 0777, true)) {
                    Log::error('Gagal membuat direktori public: ' . $publicDir);
                } else {
                    // Salin file ke public jika direktori berhasil dibuat
                    copy($storagePath, $publicPath);
                }
            } else {
                // Salin file ke public
                copy($storagePath, $publicPath);
            }
            
            imagedestroy($canvas);
            
            if (!$success) {
                Log::error('Gagal menyimpan gambar');
                return false;
            }
            
            // Verifikasi file benar-benar ada
            if (!file_exists($storagePath)) {
                Log::error('File tidak ditemukan setelah disimpan: ' . $storagePath);
                return false;
            }
            
            // Update record poster
            $poster->hasil_final = $filename;
            $poster->save();
            
            Log::info('Berhasil memproses poster: ' . $poster->id);
            return true;
        } catch (\Exception $e) {
            Log::error('Error saat memproses poster: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Membuat teks dengan ukuran besar menggunakan teknik penskalaan
     * 
     * @param resource $canvas Gambar tujuan
     * @param string $text Teks yang akan digambar
     * @param int $y Posisi Y teks
     * @param int $textColor Warna teks
     * @param int $shadowColor Warna bayangan
     * @param string $type Tipe teks ('judul' atau 'narasi')
     * @return void
     */
    protected function createLargeTextImage($canvas, $text, $y, $textColor, $shadowColor, $type = 'judul')
    {
        Log::info('Menggunakan createLargeTextImage sebagai fallback untuk: ' . $type);
        
        // Tentukan ukuran berdasarkan tipe
        $scale = ($type === 'judul') ? 10 : 8; // Mengurangi skala dari 20/16 menjadi 10/8
        $fontSize = 5; // Ukuran font maksimal
        
        // Untuk teks narasi yang panjang, pisahkan menjadi beberapa baris
        $lines = [];
        if ($type === 'narasi') {
            // Bagi teks menjadi baris-baris dengan panjang maksimal 40 karakter
            $lines = str_split($text, 40);
        } else {
            // Untuk judul, gunakan teks asli
            $lines = [$text];
        }
        
        $lineHeight = 40; // Jarak antar baris
        $totalHeight = count($lines) * $lineHeight;
        
        // Buat canvas khusus untuk teks dengan ukuran lebih besar
        $textImage = imagecreatetruecolor(2000, $totalHeight);
        imagefill($textImage, 0, 0, imagecolorallocatealpha($textImage, 0, 0, 0, 127));
        imagealphablending($textImage, true);
        imagesavealpha($textImage, true);
        
        // Gambar teks dengan font default
        foreach ($lines as $index => $line) {
            $x = 0; // Mulai dari kiri
            $currentY = $index * $lineHeight;
            
            if ($type === 'judul') {
                // Gunakan font default yang lebih besar dan tebal
                imagestring($textImage, $fontSize, $x, $currentY, strtoupper($line), $textColor); // Uppercase untuk judul
            } else {
                // Font normal untuk narasi
                imagestring($textImage, $fontSize, $x, $currentY, $line, $textColor);
            }
        }
        
        // Buat gambar baru dengan ukuran lebih besar
        $scaledWidth = imagesx($textImage) * $scale;
        $scaledHeight = imagesy($textImage) * $scale;
        $scaledImage = imagecreatetruecolor($scaledWidth, $scaledHeight);
        
        // Atur background transparan
        imagealphablending($scaledImage, false);
        imagesavealpha($scaledImage, true);
        $transparent = imagecolorallocatealpha($scaledImage, 0, 0, 0, 127);
        imagefill($scaledImage, 0, 0, $transparent);
        
        // Resize dengan interpolasi
        imagealphablending($scaledImage, true);
        imagecopyresampled(
            $scaledImage, $textImage,
            0, 0, 0, 0,
            $scaledWidth, $scaledHeight,
            imagesx($textImage), imagesy($textImage)
        );
        
        // Tentukan posisi X untuk pemusatan
        $centerX = (imagesx($canvas) - $scaledWidth) / 2;
        
        // Tambahkan teks yang sudah dirender ke canvas utama
        imagecopy(
            $canvas, $scaledImage,
            $centerX, $y - $scaledHeight, 0, 0,
            $scaledWidth, $scaledHeight
        );
        
        // Bebaskan memori
        imagedestroy($textImage);
        imagedestroy($scaledImage);
    }
    
    /**
     * Membatasi teks narasi
     *
     * @param string $text
     * @param int $length
     * @return string
     */
    private function limitText(string $text, int $length = 1000): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }
    
    /**
     * Membuat teks multi-line dengan word wrap
     *
     * @param string $text
     * @param int $font_size
     * @param string $font_path
     * @param int $max_width
     * @return array
     */
    private function wordWrapText(string $text, int $font_size, string $font_path, int $max_width = 1600): array
    {
        // Split teks menjadi kata-kata
        $words = explode(' ', $text);
        $lines = [];
        $current_line = '';
        
        foreach ($words as $word) {
            // Coba tambahkan kata ke baris saat ini
            $test_line = $current_line . ' ' . $word;
            $test_line = ltrim($test_line); // Hapus spasi di awal
            
            // Hitung lebar teks dengan font TTF
            $bbox = imagettfbbox($font_size, 0, $font_path, $test_line);
            $text_width = $bbox[2] - $bbox[0];
            
            // Jika melebihi lebar maksimum, mulai baris baru
            if ($text_width > $max_width && $current_line !== '') {
                $lines[] = $current_line;
                $current_line = $word;
            } else {
                $current_line = $test_line;
            }
        }
        
        // Tambahkan baris terakhir
        if ($current_line !== '') {
            $lines[] = $current_line;
        }
        
        // Batasi jumlah baris maksimum untuk menghindari terlalu banyak baris
        if (count($lines) > 15) {
            $lines = array_slice($lines, 0, 15);
            $lines[14] .= '...';
        }
        
        return $lines;
    }
} 