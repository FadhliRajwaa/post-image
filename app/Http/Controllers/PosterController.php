<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PosterController extends Controller
{
    protected ImageProcessor $imageProcessor;
    
    /**
     * Constructor
     */
    public function __construct(ImageProcessor $imageProcessor)
    {
        $this->imageProcessor = $imageProcessor;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posters = Poster::all();
        return view('admin', compact('posters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin');
    }

    /**
     * Download gambar poster
     */
    public function downloadImage($id)
    {
        try {
            $poster = Poster::findOrFail($id);
            
            // Hapus hasil final lama jika ada di direktori download
            $downloadPath = storage_path('app/public/posters/download/' . $poster->id . '.jpg');
            if (file_exists($downloadPath)) {
                unlink($downloadPath);
            }
            
            // Gunakan ImageProcessor untuk membuat file download
            if (!$this->imageProcessor->processPoster($poster)) {
                return redirect()->back()->with('error', 'Gagal membuat gambar untuk didownload.');
            }
            
            // Salin file hasil ke direktori download
            $sourcePath = Storage::disk('public')->path($poster->hasil_final);
            $targetPath = storage_path('app/public/posters/download/' . $poster->id . '.jpg');
            
            // Pastikan direktori ada
            $downloadDir = dirname($targetPath);
            if (!is_dir($downloadDir)) {
                mkdir($downloadDir, 0755, true);
            }
            
            // Salin file
            copy($sourcePath, $targetPath);
            
            // Cek apakah file ada
            if (!file_exists($targetPath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan setelah diproses.');
            }
            
            // Download file
            return response()->download($targetPath, 'poster-' . $poster->id . '.jpg');
            
        } catch (\Exception $e) {
            Log::error('Error saat download gambar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh gambar.');
        }
    }
    
    /**
     * Metode alternatif yang lebih sederhana untuk membuat gambar download
     */
    private function createSimpleDownloadImage(Poster $poster)
    {
        try {
            // Tingkatkan batas waktu eksekusi
            set_time_limit(300); // 5 menit
            
            Log::info('Membuat gambar download sederhana untuk poster ID: ' . $poster->id);
            
            // Pastikan path file gambar ada
            $gambarPath = Storage::disk('public')->path($poster->gambar);
            $framePath = Storage::disk('public')->path($poster->frame);
            
            if (!file_exists($gambarPath) || !file_exists($framePath)) {
                Log::error('File gambar atau frame tidak ditemukan untuk download');
                return false;
            }
            
            // Cek path font Arial Black dan Calibri Bold
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
            
            // Buat gambar baru dengan ukuran 2000x2000 pixel
            $image = imagecreatetruecolor(2000, 2000);
            
            // Buat warna putih untuk background
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $white);
            
            // Buka gambar utama
            $mainImage = $this->openImage($gambarPath);
            if (!$mainImage) {
                Log::error('Gagal membuka gambar utama untuk download');
                return false;
            }
            
            // Buka frame
            $frameImage = $this->openImage($framePath);
            if (!$frameImage) {
                Log::error('Gagal membuka frame untuk download');
                imagedestroy($mainImage);
                return false;
            }
            
            // Resize gambar utama ke 2000x2000 dan tempatkan di canvas
            imagecopyresampled($image, $mainImage, 0, 0, 0, 0, 2000, 2000, imagesx($mainImage), imagesy($mainImage));
            
            // Tempatkan frame di atas gambar utama
            imagecopyresampled($image, $frameImage, 0, 0, 0, 0, 2000, 2000, imagesx($frameImage), imagesy($frameImage));
            
            // Bebaskan memori
            imagedestroy($mainImage);
            imagedestroy($frameImage);
            
            // Buat warna untuk teks
            $textColor = imagecolorallocate($image, 255, 255, 255);
            
            // Verifikasi file font ada
            if (!file_exists($arialBlackPath)) {
                Log::error('Font Arial Black tidak ditemukan di: ' . $arialBlackPath);
            }
            
            if (!file_exists($calibriBoldPath)) {
                Log::error('Font Calibri Bold tidak ditemukan di: ' . $calibriBoldPath);
            }
            
            // Ukuran font yang lebih besar
            $fontSizeJudul = 400; // Ukuran font untuk judul (dikurangi dari 1000)
            $fontSizeNarasi = 200; // Ukuran font untuk narasi (dikurangi dari 800)
            
            // Posisi teks di tengah bawah
            $judul = $poster->judul;
            $narasi = $poster->narasi; // Gunakan narasi lengkap tanpa limit
            
            // Hitung lebar teks untuk pemusatan
            $bbox = imagettfbbox($fontSizeJudul, 0, $arialBlackPath, $judul);
            $judulWidth = $bbox[2] - $bbox[0];
            $judulX = (2000 - $judulWidth) / 2;
            
            $bbox = imagettfbbox($fontSizeNarasi, 0, $calibriBoldPath, $narasi);
            $narasiWidth = $bbox[2] - $bbox[0];
            $narasiX = (2000 - $narasiWidth) / 2;
            
            // Posisi Y untuk judul dan narasi
            $judulY = 1700;  // Menyesuaikan dari 1600 ke 1700
            $narasiY = 1850; // Menyesuaikan dari 1800 ke 1850
            
            // Coba gunakan font TTF
            $judulSuccess = false;
            $narasiSuccess = false;
            
            try {
                // Tambahkan judul dengan Arial Black
                if (file_exists($arialBlackPath)) {
                    $result = imagettftext($image, $fontSizeJudul, 0, $judulX, $judulY, $textColor, $arialBlackPath, $judul);
                    if ($result !== false) {
                        $judulSuccess = true;
                        Log::info('Berhasil menggunakan font TTF untuk judul: ' . $arialBlackPath);
                    } else {
                        Log::error('Gagal menggunakan font TTF untuk judul meskipun file ada');
                    }
                }
                
                // Tambahkan narasi dengan Calibri Bold
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
                        
                        $result = imagettftext($image, $fontSizeNarasi, 0, $lineX, $lineY, $textColor, $calibriBoldPath, $line);
                        
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
                Log::error('Error saat menambahkan teks TTF: ' . $e->getMessage());
            }
            
            // Jika TTF gagal dengan GD native, coba dengan pendekatan lain
            if (!$judulSuccess || !$narasiSuccess) {
                try {
                    Log::info('Mencoba pendekatan alternatif untuk menambahkan teks');
                    
                    // Coba gunakan pendekatan lain: copy file font ke temp directory
                    $tempDir = sys_get_temp_dir();
                    
                    if (!$judulSuccess && file_exists($arialBlackPath)) {
                        $tempArialPath = $tempDir . '/ariblk.ttf';
                        copy($arialBlackPath, $tempArialPath);
                        
                        if (file_exists($tempArialPath)) {
                            Log::info('Menyalin font Arial Black ke temp: ' . $tempArialPath);
                            $result = imagettftext($image, $fontSizeJudul, 0, $judulX, $judulY, $textColor, $tempArialPath, $judul);
                            if ($result !== false) {
                                $judulSuccess = true;
                                Log::info('Berhasil menggunakan font TTF (temp) untuk judul');
                            }
                            // Hapus file temp
                            @unlink($tempArialPath);
                        }
                    }
                    
                    if (!$narasiSuccess && file_exists($calibriBoldPath)) {
                        $tempCalibriPath = $tempDir . '/calibrib.ttf';
                        copy($calibriBoldPath, $tempCalibriPath);
                        
                        if (file_exists($tempCalibriPath)) {
                            Log::info('Menyalin font Calibri Bold ke temp: ' . $tempCalibriPath);
                            $result = imagettftext($image, $fontSizeNarasi, 0, $narasiX, $narasiY, $textColor, $tempCalibriPath, $narasi);
                            if ($result !== false) {
                                $narasiSuccess = true;
                                Log::info('Berhasil menggunakan font TTF (temp) untuk narasi');
                            }
                            // Hapus file temp
                            @unlink($tempCalibriPath);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error saat mencoba pendekatan alternatif: ' . $e->getMessage());
                }
            }
            
            // Jika TTF tetap gagal, gunakan metode createLargeTextImage sebagai fallback
            if (!$judulSuccess) {
                Log::warning('Menggunakan fallback untuk judul karena semua metode TTF gagal');
                $this->createLargeTextImage($image, $judul, 1700, $textColor, $textColor, 'judul');
            }
            
            if (!$narasiSuccess) {
                Log::warning('Menggunakan fallback untuk narasi karena semua metode TTF gagal');
                $this->createLargeTextImage($image, $narasi, 1800, $textColor, $textColor, 'narasi');
            }
            
            // Simpan gambar
            $downloadDir = 'posters/download';
            $outputPath = storage_path('app/public/' . $downloadDir . '/' . $poster->id . '.jpg');
            $publicPath = public_path('storage/' . $downloadDir . '/' . $poster->id . '.jpg');
            
            // Pastikan direktori ada
            if (!file_exists(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0755, true);
            }
            if (!file_exists(dirname($publicPath))) {
                mkdir(dirname($publicPath), 0755, true);
            }
            
            // Simpan gambar ke storage dan public
            imagejpeg($image, $outputPath, 90);
            imagejpeg($image, $publicPath, 90);
            
            // Bebaskan memori
            imagedestroy($image);
            
            Log::info('Berhasil membuat gambar download sederhana');
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error saat membuat gambar download: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Membuka gambar dari path yang diberikan
     * 
     * @param string $path Path gambar
     * @return resource|false Resource gambar atau false jika gagal
     */
    private function openImage($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        try {
            if ($extension === 'jpg' || $extension === 'jpeg') {
                return imagecreatefromjpeg($path);
            } elseif ($extension === 'png') {
                return imagecreatefrompng($path);
            } elseif ($extension === 'gif') {
                return imagecreatefromgif($path);
            } else {
                Log::error('Format gambar tidak didukung: ' . $extension);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Gagal membuka gambar: ' . $e->getMessage());
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
        
        // Buat canvas khusus untuk teks dengan ukuran lebih besar
        $textImage = imagecreatetruecolor(2000, 800); // Meningkatkan tinggi dari 500 ke 800
        imagefill($textImage, 0, 0, imagecolorallocatealpha($textImage, 0, 0, 0, 127));
        imagealphablending($textImage, true);
        imagesavealpha($textImage, true);
        
        // Gambar teks dengan font default
        $x = 0;
        if ($type === 'judul') {
            // Gunakan font default yang lebih besar dan tebal
            imagestring($textImage, 5, 0, 0, strtoupper($text), $textColor); // Uppercase untuk judul
        } else {
            // Font normal untuk narasi
            imagestring($textImage, 5, 0, 0, $text, $textColor);
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
     * Membuat gambar khusus untuk download dengan font TTF
     */
    private function createDownloadImage(Poster $poster)
    {
        try {
            // Tingkatkan batas waktu eksekusi
            set_time_limit(120); // 2 menit
            
            Log::info('Membuat gambar download khusus untuk poster ID: ' . $poster->id);
            
            // Pastikan path file gambar ada
            $gambarPath = Storage::disk('public')->path($poster->gambar);
            $framePath = Storage::disk('public')->path($poster->frame);
            
            if (!file_exists($gambarPath) || !file_exists($framePath)) {
                Log::error('File gambar atau frame tidak ditemukan untuk download');
                return false;
            }
            
            // Buat direktori posters jika belum ada
            $postersDir = public_path('storage/posters');
            if (!is_dir($postersDir)) {
                mkdir($postersDir, 0777, true);
            }
            
            // Nama file hasil
            $filename = 'posters/' . time() . '_download_' . uniqid() . '.jpg';
            $fullPath = public_path('storage/' . $filename);
            $storagePath = Storage::disk('public')->path($filename);
            
            Log::info('Akan menyimpan file download ke: ' . $fullPath);
            
            // Buat canvas
            $canvas = imagecreatetruecolor(2000, 2000);
            
            // Load gambar utama
            $extension = pathinfo($gambarPath, PATHINFO_EXTENSION);
            $mainImage = null;
            
            if (strtolower($extension) === 'jpg' || strtolower($extension) === 'jpeg') {
                $mainImage = imagecreatefromjpeg($gambarPath);
            } elseif (strtolower($extension) === 'png') {
                $mainImage = imagecreatefrompng($gambarPath);
            }
            
            if (!$mainImage) {
                Log::error('Gagal membaca gambar utama untuk download');
                return false;
            }
            
            // Resize gambar utama ke 2000x2000
            $width = imagesx($mainImage);
            $height = imagesy($mainImage);
            
            $resizedMain = imagecreatetruecolor(2000, 2000);
            imagecopyresampled($resizedMain, $mainImage, 0, 0, 0, 0, 2000, 2000, $width, $height);
            imagedestroy($mainImage);
            
            // Copy gambar utama ke canvas
            imagecopy($canvas, $resizedMain, 0, 0, 0, 0, 2000, 2000);
            imagedestroy($resizedMain);
            
            // Load frame overlay
            $frame = imagecreatefrompng($framePath);
            if (!$frame) {
                Log::error('Gagal membaca frame untuk download');
                imagedestroy($canvas);
                return false;
            }
            
            $frameWidth = imagesx($frame);
            $frameHeight = imagesy($frame);
            
            // Aktifkan alpha blending
            imagealphablending($canvas, true);
            
            // Resize frame jika perlu
            if ($frameWidth != 2000 || $frameHeight != 2000) {
                $resizedFrame = imagecreatetruecolor(2000, 2000);
                imagealphablending($resizedFrame, false);
                imagesavealpha($resizedFrame, true);
                imagecopyresampled($resizedFrame, $frame, 0, 0, 0, 0, 2000, 2000, $frameWidth, $frameHeight);
                imagedestroy($frame);
                $frame = $resizedFrame;
            }
            
            // Overlay frame ke canvas
            imagecopy($canvas, $frame, 0, 0, 0, 0, 2000, 2000);
            imagedestroy($frame);
            
            // Tambahkan teks
            $white = imagecolorallocate($canvas, 255, 255, 255);
            
            // Cek path font Arial Black dan Calibri Bold
            $arialBlackPath = realpath(public_path('fonts/new/ariblk.ttf'));
            $calibriBoldPath = realpath(public_path('fonts/new/calibri-bold.ttf'));
            
            // Log info tambahan untuk debugging
            Log::info('Path font Arial Black untuk download: ' . $arialBlackPath);
            Log::info('Path font Calibri Bold untuk download: ' . $calibriBoldPath);
            
            // Coba juga path alternatif jika font tidak ditemukan
            if (!$arialBlackPath) {
                $arialBlackPath = realpath(base_path('fonts/new/ariblk.ttf'));
                Log::info('Mencoba path alternatif untuk Arial Black: ' . $arialBlackPath);
            }
            
            if (!$calibriBoldPath) {
                $calibriBoldPath = realpath(base_path('fonts/new/calibri-bold.ttf'));
                Log::info('Mencoba path alternatif untuk Calibri Bold: ' . $calibriBoldPath);
            }
            
            // Ukuran font yang lebih besar
            $fontSizeJudul = 400; // Ukuran font untuk judul (dikurangi dari 1000)
            $fontSizeNarasi = 200;  // Ukuran font untuk narasi (dikurangi dari 800)
            
            // Posisi teks di tengah bawah
            $judul = $poster->judul;
            $narasi = substr($poster->narasi, 0, 1000) . (strlen($poster->narasi) > 1000 ? '...' : '');
            
            // Hitung lebar teks untuk pemusatan
            $bbox = imagettfbbox($fontSizeJudul, 0, $arialBlackPath, $judul);
            $judulWidth = $bbox[2] - $bbox[0];
            $judulX = (2000 - $judulWidth) / 2;
            
            $bbox = imagettfbbox($fontSizeNarasi, 0, $calibriBoldPath, $narasi);
            $narasiWidth = $bbox[2] - $bbox[0];
            $narasiX = (2000 - $narasiWidth) / 2;
            
            // Posisi Y untuk judul dan narasi
            $judulY = 1700;  // Menyesuaikan dari 1600 ke 1700
            $narasiY = 1850; // Menyesuaikan dari 1800 ke 1850
            
            // Coba gunakan font TTF
            $judulSuccess = false;
            $narasiSuccess = false;
            
            try {
                // Tambahkan judul dengan Arial Black
                if (file_exists($arialBlackPath)) {
                    $result = imagettftext($canvas, $fontSizeJudul, 0, $judulX, $judulY, $white, $arialBlackPath, $judul);
                    if ($result !== false) {
                        $judulSuccess = true;
                        Log::info('Berhasil menggunakan font TTF untuk judul download: ' . $arialBlackPath);
                    } else {
                        Log::error('Gagal menggunakan font TTF untuk judul download meskipun file ada');
                    }
                }
                
                // Tambahkan narasi dengan Calibri Bold
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
                            Log::error('Gagal menggunakan font TTF untuk narasi download baris ' . ($index + 1));
                            break;
                        }
                    }
                    
                    if ($narasiSuccess) {
                        Log::info('Berhasil menggunakan font TTF untuk narasi download: ' . $calibriBoldPath);
                    } else {
                        Log::error('Gagal menggunakan font TTF untuk narasi download meskipun file ada');
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error saat menambahkan teks TTF untuk download: ' . $e->getMessage());
            }
            
            // Jika TTF gagal, gunakan metode createLargeTextImage sebagai fallback
            if (!$judulSuccess) {
                Log::warning('Menggunakan fallback untuk judul download karena TTF gagal');
                $this->createLargeTextImage($canvas, $judul, 1700, $white, $white, 'judul');
            }
            
            if (!$narasiSuccess) {
                Log::warning('Menggunakan fallback untuk narasi download karena TTF gagal');
                $this->createLargeTextImage($canvas, $narasi, 1800, $white, $white, 'narasi');
            }
            
            // Pastikan direktori ada
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            
            // Simpan gambar langsung ke public/storage
            $success = imagejpeg($canvas, $fullPath, 95);
            
            // Simpan juga ke storage/app/public
            $storageDir = dirname($storagePath);
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0777, true);
            }
            copy($fullPath, $storagePath);
            
            imagedestroy($canvas);
            
            if (!$success) {
                Log::error('Gagal menyimpan gambar download ke: ' . $fullPath);
                return false;
            }
            
            // Verifikasi file benar-benar ada
            if (!file_exists($fullPath)) {
                Log::error('File download tidak ditemukan setelah disimpan: ' . $fullPath);
                return false;
            } else {
                Log::info('File download berhasil disimpan dan terverifikasi: ' . $fullPath);
                // Ukuran file
                $fileSize = filesize($fullPath);
                Log::info('Ukuran file download: ' . $fileSize . ' bytes');
                
                if ($fileSize <= 0) {
                    Log::error('File download kosong (0 bytes)');
                    return false;
                }
            }
            
            // Update database dengan path relatif terhadap storage
            $poster->hasil_final = $filename;
            $poster->save();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error saat membuat gambar download: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Menambahkan teks tanpa bayangan
     */
    protected function addTextWithShadow($image, $text, $centerX, $y, $color, $shadowColor, $fontSize = 5, $shadowSize = 3)
    {
        // Hitung lebar teks
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $x = $centerX - ($textWidth / 2);
        
        // Gambar teks utama tanpa bayangan
        imagestring($image, $fontSize, $x, $y, $text, $color);
    }
    
    /**
     * Membuat gambar download dengan font default (built-in)
     * 
     * @param resource $canvas
     * @param Poster $poster
     * @return bool
     */
    protected function createDownloadWithDefaultFont($canvas, Poster $poster)
    {
        try {
            Log::info('Menggunakan font default untuk download poster ID: ' . $poster->id);
            
            // Tambahkan teks
            $white = imagecolorallocate($canvas, 255, 255, 255);
            $black = imagecolorallocate($canvas, 0, 0, 0);
            
            // Tambahkan teks judul
            $judul = $poster->judul;
            $fontSize = 5; // Font size untuk built-in font (1-5) - Gunakan nilai maksimal
            
            // Hitung lebar teks untuk judul
            $textWidth = imagefontwidth($fontSize) * strlen($judul);
            $textX = (2000 - $textWidth) / 2;
            $textY = 1700;
            
            // Tambahkan teks judul utama tanpa bayangan
            imagestring($canvas, $fontSize, $textX, $textY, $judul, $white);
            
            // Tambahkan teks narasi
            $narasi = substr($poster->narasi, 0, 1000) . (strlen($poster->narasi) > 1000 ? '...' : '');
            $this->addTextWithShadow($canvas, $narasi, 1000, 1800, $white, null, 5);
            
            // Generate nama file
            $filename = 'posters/' . time() . '_download_default_' . uniqid() . '.jpg';
            $fullPath = public_path('storage/' . $filename);
            $storagePath = Storage::disk('public')->path($filename);
            
            // Pastikan direktori ada
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            
            // Simpan gambar langsung ke public/storage
            $success = imagejpeg($canvas, $fullPath, 95);
            
            // Simpan juga ke storage/app/public
            $storageDir = dirname($storagePath);
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0777, true);
            }
            copy($fullPath, $storagePath);
            
            imagedestroy($canvas);
            
            if (!$success) {
                Log::error('Gagal menyimpan gambar download dengan font default');
                return false;
            }
            
            // Update record poster
            $poster->hasil_final = $filename;
            $poster->save();
            
            Log::info('Berhasil memproses download dengan font default: ' . $poster->id);
            return true;
        } catch (\Exception $e) {
            Log::error('Error saat memproses download dengan font default: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'narasi' => 'required|max:2000',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'frame' => 'required|image|mimes:png|max:2048',
        ]);
        
        try {
            // Upload gambar
            $gambarPath = $request->file('gambar')->store('uploads', 'public');
            $framePath = $request->file('frame')->store('uploads', 'public');
            
            // Buat record poster
            $poster = Poster::create([
                'judul' => $validated['judul'],
                'narasi' => $validated['narasi'],
                'gambar' => $gambarPath,
                'frame' => $framePath,
            ]);
            
            // Proses gambar menggunakan ImageProcessor untuk ukuran font yang besar
            $success = $this->imageProcessor->processPoster($poster);
            
            if ($success) {
                return redirect()->route('admin')
                    ->with('success', 'Poster berhasil disimpan.');
            } else {
                // Poster tetap disimpan meskipun gambar gagal diproses
                return redirect()->route('admin')
                    ->with('warning', 'Poster berhasil disimpan, tetapi gagal memproses gambar hasil akhir.');
            }
        } catch (\Exception $e) {
            Log::error('Error saving poster: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $poster = Poster::findOrFail($id);
        return view('show', compact('poster'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $poster = Poster::findOrFail($id);
        return view('edit', compact('poster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required|max:50',
            'narasi' => 'required|max:2000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'frame' => 'nullable|image|mimes:png|max:2048',
        ]);

        $poster = Poster::findOrFail($id);
        $poster->judul = $request->judul;
        $poster->narasi = $request->narasi;
        
        if ($request->hasFile('gambar')) {
            // Hapus file lama jika ada
            if ($poster->gambar) {
                Storage::disk('public')->delete($poster->gambar);
            }
            $gambarPath = $request->file('gambar')->store('uploads', 'public');
            $poster->gambar = $gambarPath;
        }
        
        if ($request->hasFile('frame')) {
            // Hapus file lama jika ada
            if ($poster->frame) {
                Storage::disk('public')->delete($poster->frame);
            }
            $framePath = $request->file('frame')->store('uploads', 'public');
            $poster->frame = $framePath;
        }
        
        // Hapus hasil final lama jika ada
        if ($poster->hasil_final) {
            Storage::disk('public')->delete($poster->hasil_final);
            $poster->hasil_final = null;
        }
        
        $poster->save();
        
        // Proses ulang gambar dan generate hasil final baru
        $this->imageProcessor->processPoster($poster);
        
        return redirect()->route('posters.index')
            ->with('success', 'Poster berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $poster = Poster::findOrFail($id);
        
        // Hapus gambar-gambar terkait
        if ($poster->gambar) {
            Storage::disk('public')->delete($poster->gambar);
        }
        if ($poster->frame) {
            Storage::disk('public')->delete($poster->frame);
        }
        if ($poster->hasil_final) {
            Storage::disk('public')->delete($poster->hasil_final);
        }
        
        $poster->delete();
        
        return redirect()->route('posters.index')
            ->with('success', 'Poster berhasil dihapus!');
    }
    
    /**
     * Remove all resources from storage.
     */
    public function destroyAll()
    {
        $posters = Poster::all();
        
        foreach ($posters as $poster) {
            // Hapus gambar-gambar terkait
            if ($poster->gambar) {
                Storage::disk('public')->delete($poster->gambar);
            }
            if ($poster->frame) {
                Storage::disk('public')->delete($poster->frame);
            }
            if ($poster->hasil_final) {
                Storage::disk('public')->delete($poster->hasil_final);
            }
        }
        
        Poster::truncate();
        
        return redirect()->route('posters.index')
            ->with('success', 'Semua poster berhasil dihapus!');
    }
    
    /**
     * Display the home page with all posters.
     */
    public function homeIndex()
    {
        $posters = Poster::all();
        return view('index', compact('posters'));
    }
    
    /**
     * Display the download page for a specific poster.
     */
    public function downloadPage($id)
    {
        $poster = Poster::findOrFail($id);
        return view('download', compact('poster'));
    }
    
    /**
     * Regenerate image for a poster
     */
    public function regenerate($id)
    {
        try {
            $poster = Poster::findOrFail($id);
            
            // Hapus hasil final lama jika ada
            if ($poster->hasil_final) {
                Storage::disk('public')->delete($poster->hasil_final);
                $poster->hasil_final = null;
                $poster->save();
            }
            
            // Gunakan ImageProcessor untuk regenerasi dengan ukuran font besar
            if (!$this->imageProcessor->processPoster($poster)) {
                return redirect()->back()->with('error', 'Gagal meregenerasi gambar poster.');
            }
            
            return redirect()->back()->with('success', 'Poster berhasil diregenerasi.');
        } catch (\Exception $e) {
            Log::error('Error saat regenerasi poster: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat meregenerasi poster.');
        }
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
