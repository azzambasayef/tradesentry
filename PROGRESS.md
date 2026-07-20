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
- [x] Buat sistem autentikasi murni (Login, Register, Logout).
- [x] Buat halaman *Dashboard* (Welcome Page).
- [x] Buat halaman *Country Dashboard* untuk menampilkan daftar negara termonitor.
- [x] Buat halaman *Country Detail* untuk melihat metrik spesifik per negara.

## Fase 3-6 — Integrasi API Data Global & Risk Engine
**Status:** ✅ Selesai
- [x] Fase 3: World Bank API & Caching
- [x] Fase 4: Weather API (Open-Meteo) & Leaflet Maps
- [x] Fase 5: Currency API & Chart.js
- [x] Fase 6: Risk Scoring Engine (algoritma bobot custom)

## Fase 7 — News Intelligence
**Status:** ✅ Selesai
- [x] Lexicon Sentiment Analysis PHP
- [x] Integrasi BBC RSS & Google News RSS
- [x] Dasbor pencarian berita per negara

## Fase 8 — Port Location Dashboard & Live Operations
**Status:** ✅ Selesai
- [x] Peta interaktif Leaflet.js
- [x] Integrasi ribuan Pelabuhan Logistik & 500 Armada Kapal Kargo
- [x] Pembuatan **Backend Geoprocessing Engine (Node.js)** menggunakan Algoritma Dijkstra (*searoute-js*) untuk menggambar garis lintasan kapal yang menghindari benua secara otomatis
- [x] UI/UX Perbaikan (Navbar responsif & Interaktivitas *sidebar* negara di Dasbor)

## Fase berikutnya:
- **Fase 9:** Data Visualization Dashboard (trend GDP/Inflation/Currency/Risk)
- **Fase 10:** Country Comparison Engine
