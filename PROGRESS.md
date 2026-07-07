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

---

## Fase berikutnya:
- **Fase 0 (lanjutan):** Buat 18 migration files + Eloquent Models + Seeder (positive/negative words, risk_weights)
- **Fase 1:** Auth + base layout (Bootstrap 5, navigasi)
