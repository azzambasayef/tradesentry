# TradeSentry — Progress Log

## Project Info
- **Nama:** TradeSentry (Global Supply Chain Risk Intelligence Platform)
- **Pemilik:** Azzam — Sistem Informasi Semester 4, Universitas Malikussaleh
- **Stack:** Laravel 12 + MySQL + Bootstrap 5 + Chart.js + Leaflet.js

---

## Fase 0 — Setup Project & Koneksi Database

**Status:** ✅ Selesai
**Tanggal:** 2026-07-01

### Yang dikerjakan:
- [x] Inisialisasi project Laravel 12 via Composer
- [x] Konfigurasi `.env` untuk MySQL XAMPP (database: `tradesentry`)
- [x] Buat database `tradesentry` di MySQL
- [x] Jalankan migration default Laravel (users, cache, jobs)
- [x] Test koneksi — server Laravel berhasil jalan dan merespons HTTP 200
- [x] Buat 17 Migration & Models lengkap dengan schema sesuai ERD
- [x] Buat Seeder (Country, RiskWeight, Sentiment, User) sebagai master data lokal

### Keputusan desain:
- Nama project: **TradeSentry**
- Struktur folder: Standar Laravel MVC + folder `Services/`
- Naming convention: snake_case untuk tabel/kolom, PascalCase untuk model/controller
- APP_TIMEZONE: Asia/Jakarta
- Database user: root (tanpa password, standar XAMPP)
- Sumber Data Negara: Master data lokal di database via Seeder (disetujui dosen)

### Catatan tambahan:
- Dashboard utama nanti akan menampilkan **live map (Leaflet) + info kurs di space kecil** (permintaan dosen)
- ERD 18 tabel sudah dirancang di implementation plan, migrasi custom akan dibuat di sub-fase berikutnya

## Fase 1 & 2 — Auth, Base Layout & Country Dashboard

**Status:** ✅ Selesai
**Tanggal:** 2026-07-09

### Yang dikerjakan:
- [x] Setup layout utama `app.blade.php` dengan Bootstrap 5 dan tema *Dark Mode* elegan.
- [x] Buat sistem autentikasi manual murni (Login, Register, Logout).
- [x] Buat halaman *Dashboard* (Welcome Page).
- [x] Buat halaman *Country Dashboard* untuk menampilkan daftar negara termonitor.
- [x] Buat halaman *Country Detail* untuk melihat metrik spesifik per negara.

### Catatan tambahan:
- Sesuai kesepakatan, Auth dibuat manual agar sesuai level mahasiswa.
- Komponen-komponen lanjutan (Cuaca, Berita, Kurs, Visualisasi Data) sudah disiapkan kotak *placeholder*-nya untuk diintegrasikan pada fase selanjutnya.

---

## Fase berikutnya:
- **Fase 3:** Integrasi API World Bank (GDP, inflasi, populasi) & *Data Fetching/Caching*.
