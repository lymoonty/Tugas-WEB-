# Hiro Petshop - Aplikasi E-commerce untuk Kebutuhan Hewan Peliharaan

Aplikasi petshop online sederhana yang dibangun dengan PHP native tanpa framework untuk penjualan kebutuhan hewan peliharaan.

## Fitur yang Tersedia

### CRUD Operations
- **Create**: Form tambah data produk dengan validasi server-side
- **Read**: Tampilan tabel daftar produk dengan sorting berdasarkan created_at DESC
- **Read Detail**: Halaman khusus untuk menampilkan detail lengkap per item
- **Update**: Form edit dengan data terisi otomatis
- **Delete**: Tombol hapus dengan konfirmasi modal menggunakan metode POST

### Fitur Tambahan
- Pencarian produk berdasarkan nama produk, kategori, dan deskripsi
- Pagination dengan 5 item per halaman
- Upload gambar produk dengan validasi file
- Proteksi keamanan:
  - Sanitasi data input untuk mencegah SQL Injection
  - Escaping output untuk mencegah XSS
  - Prepared statement untuk semua query SQL
- Pesan error yang informatif tanpa menampilkan stack trace
- Desain responsif dengan Bootstrap 5

## Kebutuhan Sistem

- **PHP Version**: 7.4 atau lebih tinggi
- **Database**: MySQL 5.7 atau lebih tinggi
- **Web Server**: Apache/Nginx
- **Ekstensi PHP yang diperlukan**:
  - PDO
  - PDO_MySQL
  - GD (untuk processing gambar)
  - Fileinfo (untuk validasi tipe file)

## Panduan Instalasi dan Konfigurasi

### 1. Clone atau Download Repository
```bash
git clone <repository-url>
cd hiro_petshop
```

### 2. Konfigurasi Database
1. Buat database baru di MySQL:
   ```sql
   CREATE DATABASE hiro_petshop;
   ```

2. Import struktur database:
   ```bash
   mysql -u username -p hiro_petshop < database.sql
   ```

### 3. Konfigurasi Koneksi Database
1. Salin file konfigurasi contoh:
   ```bash
   cp .env.example .env
   ```

2. Edit file `config/database.php` dan sesuaikan dengan konfigurasi database Anda:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'hiro_petshop');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

### 4. Konfigurasi Web Server

#### Apache
Pastikan modul rewrite diaktifkan. Buat file `.htaccess`:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Nginx
Tambahkan konfigurasi berikut di server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 5. Set Permissions
```bash
chmod -R 755 assets/images/
chmod -R 755 config/
chmod -R 755 includes/
```

### 6. Akses Aplikasi
Buka browser dan akses: `http://localhost/hiro_petshop`

## Struktur Folder Utama

```
hiro_petshop/
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS
│   ├── js/                    # JavaScript files
│   └── images/
│       └── products/          # Upload gambar produk
├── config/
│   └── database.php           # Konfigurasi database
├── includes/
│   ├── db.php                 # Koneksi database dan helper functions
│   ├── header.php             # Header template
│   └── footer.php             # Footer template
├── index.php                  # Halaman utama (daftar produk)
├── create.php                 # Form tambah produk
├── detail.php                 # Halaman detail produk
├── edit.php                   # Form edit produk
├── delete.php                 # Proses hapus produk
├── database.sql               # Struktur database dan sample data
├── .env.example              # Contoh konfigurasi environment
└── README.md                 # Dokumentasi
```

## Struktur Database

### Tabel Products
```sql
CREATE TABLE products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(255) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Penggunaan Aplikasi

### Menambah Produk
1. Klik tombol "Tambah Produk Baru" di halaman utama
2. Isi semua field yang ditandai dengan asterisk (*)
3. Upload gambar produk (opsional)
4. Klik "Simpan Produk"

### Mengedit Produk
1. Klik tombol edit (ikon pensil) pada produk yang ingin diubah
2. Perbarui informasi yang diperlukan
3. Upload gambar baru jika ingin mengganti (opsional)
4. Klik "Update Produk"

### Menghapus Produk
1. Klik tombol hapus (ikon trash) pada produk yang ingin dihapus
2. Konfirmasi penghapusan pada dialog yang muncul
3. Produk akan dihapus beserta file gambar terkait

### Mencari Produk
1. Gunakan kotak pencarian di navbar
2. Ketik kata kunci (nama produk, kategori, atau deskripsi)
3. Hasil akan ditampilkan secara real-time

## Keamanan

Aplikasi ini telah dilengkapi dengan beberapa fitur keamanan:

1. **SQL Injection Prevention**: Menggunakan prepared statements untuk semua query database
2. **XSS Prevention**: Escaping output dengan `htmlspecialchars()`
3. **Input Validation**: Validasi server-side untuk semua input form
4. **File Upload Security**: Validasi tipe file dan ukuran file
5. **CSRF Protection**: Menggunakan session untuk validasi operasi penting

## Screenshot Antarmuka Aplikasi

### Halaman Daftar Produk
![Halaman Daftar Produk](screenshots/index_page.png)

### Form Tambah Produk
![Form Tambah Produk](screenshots/create_page.png)

### Halaman Detail Produk
![Halaman Detail Produk](screenshots/detail_page.png)

## Troubleshooting

### Error Koneksi Database
Pastikan konfigurasi database di `config/database.php` sudah benar dan database sudah dibuat.

### Error Upload Gambar
Pastikan folder `assets/images/products/` memiliki permission yang tepat (755) dan ekstensi GD PHP sudah terinstall.

### Error 404
Pastikan web server Anda sudah dikonfigurasi dengan benar untuk mengarahkan semua request ke `index.php`.

## Kontribusi

1. Fork repository
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

## Lisensi

Proyek ini dilisensikan under MIT License - lihat file [LICENSE](LICENSE) untuk detailnya.

## Kontak

- **Nama**: Hiro Petshop Development Team
- **Email**: info@hiro_petshop.com
- **Website**: https://hiro_petshop.com

## Changelog

### Version 1.0.0 (2024-01-01)
- Initial release
- Basic CRUD operations
- Search functionality
- Pagination
- Image upload
- Security features