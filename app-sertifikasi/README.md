# LSPROject - Sistem Sertifikasi & Surveilans

Ini adalah aplikasi sistem operasional untuk LSPRO BSPJI Surabaya, yang dibangun menggunakan **Laravel 11**. Aplikasi ini telah disiapkan menggunakan Docker Compose agar mudah dijalankan di sistem apa pun tanpa proses instalasi yang rumit.

---

## Persiapan Awal

Pastikan Anda sudah menginstal aplikasi berikut di komputer Anda:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

---

## Tutorial Menjalankan Aplikasi (Lokal / Development)

Ikuti langkah-langkah di bawah ini secara berurutan menggunakan terminal (Command Prompt, Git Bash, atau Terminal bawaan Linux/Mac):

### Langkah 1: Konfigurasi File Environment

Gandakan file contoh konfigurasi bawaan agar dapat disesuaikan secara lokal.

```bash
cp .env.example .env
```

Kemudian buka file `.env` yang baru dibuat dan **wajib isi** variabel berikut sebelum melanjutkan:

```env
# Akun Administrator awal — wajib diisi, tidak boleh dikosongkan
ADMIN_INITIAL_USERNAME=admin
ADMIN_INITIAL_NAME=System Administrator
ADMIN_INITIAL_EMAIL=admin@example.com
ADMIN_INITIAL_PASSWORD=password_anda_di_sini
```

> **Catatan:** Nilai di atas hanyalah contoh untuk lokal. Seeder akan **berhenti otomatis** dan menampilkan pesan error jika salah satu variabel di atas masih kosong.

### Langkah 2: Menjalankan Mesin Docker

Aktifkan seluruh kontainer yang dibutuhkan (Aplikasi, Web Server, Database) agar berjalan secara otomatis di latar belakang. Sangat disarankan menambahkan _flag_ `--build` pada saat pertama kali menjalankan agar kontainer menginstal modul-modul PHP terbaru.

```bash
docker compose up -d --build
```

### Langkah 3: Menginstal Semua Dependensi

Instal _package_ atau pustaka yang dibutuhkan oleh aplikasi (PHP dan Node.js) di dalam kontainer utama.

```bash
# Dependensi Backend (PHP)
docker exec lsproject-app composer install

# Dependensi Frontend (Tailwind CSS/Vite)
docker exec lsproject-app npm install
```

### Langkah 4: Membuat Kunci Aplikasi

Aplikasi Laravel membutuhkan kunci keamanan sistem yang unik. Jalankan perintah ini untuk membuatnya:

```bash
docker exec lsproject-app php artisan key:generate
```

### Langkah 5: Mengatur Tabel Database & Mengisi Data Awal

Buat struktur tabel dan isi data awal (termasuk akun administrator) ke dalam _database_ yang sudah aktif.

```bash
# Migrasi tabel + jalankan semua seeder (termasuk pembuatan akun admin)
docker exec lsproject-app php artisan migrate --seed
```

Perintah lain yang tersedia:

```bash
# Reset seluruh database lalu jalankan ulang migrasi + seeder
docker exec lsproject-app php artisan migrate:fresh --seed

# Reset seluruh database tanpa seeder (database kosong)
docker exec lsproject-app php artisan migrate:fresh
```

### Langkah 6: Kompilasi Tampilan Aplikasi

Proses file aset (CSS/JS) agar _layout_ Tailwind CSS diaplikasikan secara optimal.

```bash
docker exec lsproject-app npm run build
```

---

## Aplikasi Siap Digunakan! 🎉

Sekarang semua instalasi telah sukses dijalankan. Silakan buka _browser_ Anda dan kunjungi halaman ini:

👉 **[http://localhost:8000](http://localhost:8000)**

### Informasi Login Masuk

Gunakan akun administrator yang telah Anda definisikan di file `.env` pada **Langkah 1** untuk masuk ke dalam sistem pertama kali.

> **Keamanan:** Kredensial login tidak lagi disimpan di dalam kode. Seluruh data akun administrator awal dibaca secara eksklusif dari file `.env` yang bersifat privat dan tidak diunggah ke repositori.

---

## Panduan Deploy ke Server Produksi

Saat mendeploy ke server produksi, pastikan langkah-langkah berikut diikuti dengan seksama:

### 1. Konfigurasi `.env` Produksi

Buat file `.env` di server dan pastikan variabel berikut diatur dengan nilai yang aman dan kuat:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.go.id

# Gunakan password yang panjang, acak, dan unik — JANGAN gunakan contoh di bawah ini
ADMIN_INITIAL_USERNAME=superadmin
ADMIN_INITIAL_NAME=Nama Administrator Anda
ADMIN_INITIAL_EMAIL=admin@domain-anda.go.id
ADMIN_INITIAL_PASSWORD=K0mb1nas1P@ssw0rdYangSangatKuatDanPanjang!
```

### 2. Jalankan Perintah Deploy

```bash
# Instal dependensi (tanpa package pengembangan)
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Generate application key
php artisan key:generate --force

# Jalankan migrasi dan seeder
php artisan migrate --force --seed

# Aktifkan cache untuk performa maksimal
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Atur Izin Folder

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

> **Penting:** Setelah akun administrator berhasil dibuat, variabel `ADMIN_INITIAL_*` di file `.env` produksi dapat dihapus untuk menjaga keamanan server.
