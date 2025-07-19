# Web Image Generator

Aplikasi web untuk membuat dan mengolah poster dengan mudah, dilengkapi dengan tambahan teks yang dapat dikustomisasi. Aplikasi ini memungkinkan pengguna untuk mengunggah gambar latar, memilih frame, dan menambahkan teks kustom dengan font Arial Black dan Calibri Bold.

## Stack Teknologi

- **Backend**: Laravel 10 (PHP Framework)
- **Database**: MySQL/MariaDB
- **Image Processing**: 
  - PHP GD Library
  - Intervention Image Package
- **Frontend**:
  - Blade Templates
  - JavaScript
  - CSS
- **Fonts**:
  - Arial Black
  - Calibri Bold
- **Server Development**:
  - XAMPP (Apache, MySQL)

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- PHP GD Extension (terinstall di XAMPP)
- Node.js dan NPM (untuk asset compilation)
- Git
- XAMPP (rekomendasi untuk pengembangan lokal)

## Langkah-langkah Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/web-image-generator.git
cd web-image-generator
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Kemudian edit file `.env` untuk mengatur koneksi database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_image_generator
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Setup Storage Link

```bash
php artisan storage:link
```

### 5. Migrasi Database

Buat database terlebih dahulu di MySQL/MariaDB, lalu jalankan migrasi:

```bash
php artisan migrate
```

### 6. Pastikan Font Tersedia

Pastikan font Arial Black dan Calibri Bold tersedia di direktori:

```
public/fonts/new/ariblk.ttf
public/fonts/new/calibri-bold.ttf
```

Jika direktori `fonts/new` belum ada, buat dahulu dan salin font ke dalamnya:

```bash
mkdir -p public/fonts/new
# Salin file font ke direktori tersebut
```

### 7. Compile Assets (Jika diperlukan)

```bash
npm run dev
```
Atau untuk production:
```bash
npm run build
```

### 8. Jalankan Server Lokal

Dengan XAMPP:
1. Pastikan Apache dan MySQL sudah berjalan
2. Letakkan project di folder `htdocs`
3. Akses melalui `http://localhost/web-image-generator/public`

Atau menggunakan server Laravel:
```bash
php artisan serve
```
Aplikasi akan berjalan di `http://localhost:8000`

## Penggunaan

1. **Admin Area**: Akses halaman admin untuk mengelola poster
2. **Membuat Poster Baru**:
   - Upload gambar latar
   - Pilih frame yang tersedia
   - Tambahkan judul dan narasi
   - Simpan poster
3. **Melihat dan Mendownload Poster**:
   - Lihat poster yang telah dibuat
   - Download poster dalam format JPG
4. **Edit dan Regenerasi Poster**:
   - Edit judul dan narasi
   - Upload gambar baru jika diperlukan
   - Regenerasi poster setelah perubahan

## Konfigurasi Ukuran Font

Aplikasi menggunakan ukuran font yang dapat diatur di:
- `app/Services/ImageProcessor.php`: Variabel `$fontSizeJudul` dan `$fontSizeNarasi` (saat ini diatur ke 90px dan 40px)
- `app/Http/Controllers/PosterController.php`: Variabel yang sama di berbagai metode

Nilai font yang disarankan:
- Untuk judul: 90-120px
- Untuk narasi: 40-60px

## Struktur Direktori Penting

- `app/Services/ImageProcessor.php`: Service untuk memproses gambar
- `app/Http/Controllers/PosterController.php`: Controller utama untuk poster
- `app/Models/Poster.php`: Model untuk poster
- `public/fonts/new/`: Direktori font
- `resources/views/`: Template Blade untuk tampilan
- `storage/app/public/posters/`: Direktori penyimpanan hasil poster
- `storage/app/public/uploads/`: Direktori penyimpanan upload gambar dan frame

## Troubleshooting

### Font Tidak Terdeteksi

Jika font tidak terdeteksi:
1. Pastikan font tersedia di direktori `public/fonts/new/`
2. Pastikan nama file font benar: `ariblk.ttf` dan `calibri-bold.ttf`
3. Cek permission direktori dan file font
4. Pastikan file font dapat diakses oleh web server (Apache)

### Ukuran Teks Tidak Sesuai

Ukuran teks dapat diatur di:
- `app/Services/ImageProcessor.php`: untuk pengaturan ukuran font
- `app/Http/Controllers/PosterController.php`: untuk pengaturan posisi teks

Untuk menyesuaikan ukuran:
```php
// Di ImageProcessor.php dan PosterController.php
$fontSizeJudul = 90; // Ubah nilai sesuai kebutuhan
$fontSizeNarasi = 40; // Ubah nilai sesuai kebutuhan
```

### Error Gambar Tidak Ditemukan

Jika muncul error gambar tidak ditemukan:
1. Pastikan sudah menjalankan `php artisan storage:link`
2. Periksa permission direktori `storage/app/public`
3. Periksa path gambar yang disimpan di database
4. Jika menggunakan XAMPP, pastikan `AllowOverride All` diaktifkan di konfigurasi Apache

### Masalah GD Library

Jika muncul error terkait GD Library:
1. Pastikan ekstensi GD terinstall dan diaktifkan di PHP
2. Cek di `phpinfo()` apakah GD sudah terinstall dengan dukungan FreeType
3. Di XAMPP, buka file `php.ini` dan pastikan extension `php_gd2.dll` tidak dikomentari

## Pengembangan Lanjutan

### Menambahkan Font Baru

1. Tambahkan file font TTF ke direktori `public/fonts/new/`
2. Update kode di `ImageProcessor.php` dan `PosterController.php` untuk menggunakan font baru

### Menambahkan Frame Baru

Frame baru harus berupa file PNG dengan transparansi untuk hasil terbaik. Upload frame melalui aplikasi atau tambahkan secara manual ke direktori `storage/app/public/uploads`.
