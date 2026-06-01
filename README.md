# LSPRO - Monorepo (Sertifikasi & Survailen)

Repository ini menggunakan arsitektur **Monorepo** yang berisi dua aplikasi terpisah namun berbagi satu *database* yang sama:
1. **Aplikasi Sertifikasi** (`app-sertifikasi`)
2. **Aplikasi Survailen** (`app-survailen`)

Kedua aplikasi ini terhubung menggunakan sistem Single Sign-On (SSO) berbasis *Session Domain*, sehingga *user* hanya perlu login satu kali di Aplikasi Sertifikasi untuk dapat mengakses kedua aplikasi.

---

## 🚀 Cara Menjalankan di Lokal (Docker)

Pastikan Anda sudah menginstall [Docker](https://www.docker.com/) dan Docker Compose di komputer Anda.

### 1. Build dan Jalankan Container
Buka terminal di dalam direktori utama project ini, lalu jalankan perintah:

```bash
docker compose up -d --build
```

### 2. Setup Database (Migrate & Seed)
Karena ini adalah instalasi awal, jalankan perintah berikut untuk membuat tabel database dan mengisi data *dummy* (termasuk *seeder* untuk akun login):

```bash
docker exec lspro_sertifikasi_app bash -c "php artisan migrate:fresh --seed"
```

### 3. Akses Aplikasi
Setelah semua *container* (Database, App Sertifikasi, dan App Survailen) berhasil berjalan dan database di-*setup*, Anda bisa mengaksesnya melalui browser:
- 🛡️ **Aplikasi Sertifikasi (Utama):** [http://localhost:8000](http://localhost:8000)
- 📊 **Aplikasi Survailen:** [http://localhost:8001](http://localhost:8001)

*(Catatan: Aplikasi Survailen tidak memiliki halaman login mandiri. Jika Anda mengakses halaman dashboard Survailen dalam keadaan belum login, Anda akan otomatis diarahkan ke halaman login Sertifikasi).*

### 4. Pengaturan Domain Host / SSO (Direkomendasikan)
Agar fitur Single Sign-On dan tombol Portal antar aplikasi dapat berjalan sempurna saat pengujian di lokal, disarankan menggunakan domain `.localhost`. 
Tambahkan konfigurasi berikut ke dalam file `hosts` di sistem operasi Anda (`/etc/hosts` untuk Linux/Mac, atau `C:\Windows\System32\drivers\etc\hosts` untuk Windows):

```text
127.0.0.1   sertifikasi.localhost
127.0.0.1   survailen.localhost
```
Setelah menambahkan baris di atas, akses aplikasi menggunakan URL berikut:
- **Sertifikasi:** http://sertifikasi.localhost:8000
- **Survailen:** http://survailen.localhost:8001

---

## 🛠️ Aturan Kolaborasi / Pembagian Tugas

- **Programmer 1 (Fokus Sertifikasi):** 
  Bekerja HANYA di dalam folder `app-sertifikasi/`. Bertanggung jawab penuh atas struktur *Master Database* (File Migrations & Seeders), sistem Autentikasi (Login/Register), dan fitur Sertifikasi.
- **Programmer 2 (Fokus Survailen):** 
  Bekerja HANYA di dalam folder `app-survailen/`. Tidak perlu mengurus *database schema* baru kecuali berkoordinasi dengan Programmer 1. Fokus mengembangkan fitur *reminder*, pembuatan surat, dan *dashboard* Survailen.

## 🛑 Menghentikan Server
Untuk mematikan seluruh *container* Docker, jalankan perintah:
```bash
docker compose down
```
