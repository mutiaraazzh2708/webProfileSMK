# 📍 Setup Instructions - SIG ESEMKA Padang

## Sistem Informasi Geografis SMK Negeri Kota Padang

### 🔧 Prerequisites
- XAMPP (Apache & MySQL)
- PHP 7.4 atau lebih tinggi
- Browser modern (Chrome, Firefox, Edge)

### 📦 Instalasi Database

1. **Buka phpMyAdmin**
   ```
   http://localhost/phpmyadmin
   ```

2. **Buat Database Baru**
   - Klik "New" di sidebar kiri
   - Nama database: `dbEsemka`
   - Collation: `utf8mb4_general_ci`
   - Klik "Create"

3. **Import Database**
   - Pilih database `dbEsemka` yang baru dibuat
   - Klik tab "Import"
   - Klik "Choose File" dan pilih: `webProfileSMK/webProfile/dbesemka.sql`
   - Klik "Go" untuk import

### 🚀 Menjalankan Aplikasi

1. **Pastikan XAMPP Apache sudah running**
   - Buka XAMPP Control Panel
   - Start Apache
   - Start MySQL

2. **Akses Aplikasi**
   ```
   http://localhost/webProfileSMK/webProfileSMK/webProfile/index.php
   ```

3. **Login Admin** (jika diperlukan)
   ```
   URL: http://localhost/webProfileSMK/webProfileSMK/webProfile/admin/login.php
   Username: rofiq
   Password: admin123 (MD5 hash sudah ada di database)
   ```

### 📁 Struktur Project

```
webProfileSMK/
├── webProfileSMK/
│   └── webProfile/
│       ├── admin/          # Panel admin
│       ├── assets/         # CSS, JS, images
│       ├── config/         # Database connection
│       ├── gambar/         # Static images
│       ├── template/       # Navbar & Footer
│       ├── uploads/        # User uploaded files
│       ├── index.php       # Homepage dengan peta
│       ├── profil.php      # Detail sekolah
│       ├── tentang.php     # About page
│       ├── kontak.php      # Contact page
│       └── dbesemka.sql    # Database dump
```

### 🗺️ Fitur Utama

1. **Peta Interaktif** - Menampilkan lokasi SMK Negeri di Kota Padang menggunakan Leaflet.js
2. **Pencarian Sekolah** - Search by nama atau alamat sekolah
3. **Detail Profil** - Informasi lengkap setiap sekolah
4. **Panel Admin** - Manajemen data sekolah, galeri, kejuruan

### ⚙️ Konfigurasi Database

File: `config/koneksi.php`
```php
$HOST = "localhost";
$USER = "root"; 
$PASS = "";
$DB   = "dbEsemka";
```

### 🐛 Troubleshooting

**Error: Database connection failed**
- Pastikan MySQL di XAMPP sudah running
- Cek nama database sudah benar: `dbEsemka`
- Pastikan file SQL sudah di-import

**Error: Page not found**
- Pastikan path URL sesuai dengan lokasi folder
- Cek Apache di XAMPP sudah running

**Peta tidak muncul**
- Cek koneksi internet (Leaflet.js menggunakan CDN)
- Pastikan data sekolah memiliki koordinat latitude & longitude

### 📊 Data Sekolah

Database berisi 15 SMK Negeri di Kota Padang dengan informasi:
- Nama sekolah & NPSN
- Alamat lengkap (kelurahan, kecamatan)
- Koordinat GPS (latitude, longitude)
- Jumlah siswa & guru
- Nama kepala sekolah
- Program kejuruan
- Galeri foto

### 🎨 Teknologi yang Digunakan

- **Backend**: PHP Native
- **Database**: MySQL (MariaDB)
- **Frontend**: Bootstrap 5
- **Maps**: Leaflet.js + OpenStreetMap
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Poppins)

---

**Developed for**: Tugas Web Profile SMK
**Last Updated**: 19 Desember 2025
