# 🍿 Backend API - Bioskop App 

Repo ini berisi Backend (Laravel 11) untuk Tugas Akhir Mobile Programming.
Fungsinya menyediakan data (API) untuk aplikasi Flutter.

---

## 🛠️ Persiapan Awal
Pastikan di laptop sudah terinstall:
1.  **XAMPP** (Nyalakan Apache & MySQL).
2.  **Composer** (Wajib, buat download library).
3.  **Git**.

---

## 🚀 Cara Install (Lakukan Sekali Saja)

Buka terminal (Git Bash / CMD / VS Code), lalu ikuti langkah ini berurutan:

### 1. Clone & Masuk Folder
```bash
git clone <LINK_REPO_GITHUB_DISINI>
cd backend-bioskop
2. Install Library
composer install
(Tunggu sampai proses download selesai)

3. Setup Environment
File settingan (.env) tidak ada di GitHub, jadi harus buat sendiri dari contoh.

Windows:
copy .env.example .env
Mac/Linux:
cp .env.example .env
Setelah itu, BUKA FILE .env di VS Code, cari bagian Database dan ubah menjadi:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_bioskop


4. Generate Key & Setup Database

Jalankan perintah ini satu per satu:
1. Buat kunci aplikasi
php artisan key:generate

2. Buat database & isi data dummy
PENTING: Pastikan sudah buat database kosong bernama 'db_bioskop' di phpMyAdmin!
php artisan migrate:fresh --seed
▶️ Cara Menjalankan Server
jalankan perintah ini (jangan di-close terminalnya):
php artisan serve
Server akan jalan di: http://127.0.0.1:8000

🔌 Dokumentasi API (Untuk Frontend)
Base URL: http://127.0.0.1:8000/api (Gunakan IP Laptop jika testing pakai HP Fisik, contoh: http://192.168.1.5:8000/api)

1. Auth
POST /register -> Body: name, email, password

POST /login -> Body: email, password (Dapat Token)

POST /logout -> Header: Authorization: Bearer <token>

2. Movies (Film)
GET /movies -> List semua film.

GET /movies/{id} -> Detail film + jadwal tayang.

3. Booking (Wajib Header Token)
GET /showtimes/{id}/seats -> Cek kursi yang sudah laku.

POST /bookings -> Beli tiket.

Body JSON:

JSON

{
    "showtime_id": 1,
    "seats": ["A1", "A2"]
}
GET /my-bookings -> Lihat tiket saya.

🧪 Akun Test (Dummy)
Database sudah otomatis diisi user dummy:

Email: user@test.com

Password: password