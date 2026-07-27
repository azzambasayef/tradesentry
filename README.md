# Global Supply Chain Risk Intelligence Platform (TradeSentry)

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)

**Live Demo:** [https://tradesentry-azzam.cleverapps.io](https://tradesentry-azzam.cleverapps.io)

**TradeSentry** adalah sebuah platform pemantauan rantai pasok global (Supply Chain Monitoring) terpadu yang dirancang untuk mengukur, melacak, dan memvisualisasikan risiko perdagangan internasional secara real-time. Proyek ini dikembangkan sebagai pemenuhan tugas akhir Ujian Akhir Semester (UAS) mata kuliah Pemrograman Web 2.

> **Dosen Pengampu:** Muhammad Ikhwani, S.Pd.I., M.Sc  
> **Dikembangkan Oleh:** Azzam Shamil Basayef (NIM: 240180159)

---

## Daftar Fitur

1. **Live Operations Dashboard**  
   Peta interaktif berbasis Leaflet.js yang memetakan lebih dari 200 negara beserta jalur pelayaran logistik dan koordinat pelabuhan secara global.
   
2. **Risk Scoring Engine**  
   Mesin kalkulasi algoritma yang menghitung skor risiko sebuah negara (skala 0-100) berdasarkan komposit dari faktor cuaca, inflasi, nilai tukar mata uang, dan sentimen berita geopolitik.

3. **News Intelligence & Sentiment Analysis**  
   Sistem agregasi berita real-time yang dilengkapi dengan fitur analisis sentimen (Positif/Negatif/Netral) menggunakan pendekatan Lexicon-based dengan kamus kata kustom berbahasa PHP.

4. **Country Comparison Engine**  
   Modul analitik untuk membandingkan data indikator ekonomi makro (GDP, Populasi, Ekspor-Impor, Inflasi) antar berbagai negara secara head-to-head.

5. **Watchlist System**  
   Fitur personalisasi bagi pengguna untuk memantau wilayah atau negara prioritas secara khusus melalui dasbor terpisah.

6. **Admin Command Center (RBAC)**  
   Dasbor manajemen terpusat dengan Role-Based Access Control untuk mengelola data pengguna, dataset pelabuhan, dan artikel internal.

7. **Interactive API Documentation**  
   Portal dokumentasi REST API yang menjelaskan seluruh endpoint yang tersedia pada sistem TradeSentry.

---

## Algoritma Kustom

Proyek ini mengimplementasikan dua algoritma kustom utama pada sisi server:

### 1. Weighted Risk Scoring Algorithm
Skor risiko suatu wilayah tidak diambil secara mentah dari API eksternal, melainkan dihitung secara mandiri melalui pembobotan (weights) berikut:
- **35% News Risk:** Mengukur sentimen berita geopolitik dan keamanan terkini. Sentimen negatif akan mendongkrak skor risiko.
- **30% Weather Risk:** Mengukur anomali cuaca ekstrem (suhu, curah hujan, kecepatan angin) yang berdampak pada logistik.
- **20% Currency Risk:** Mengukur volatilitas nilai tukar mata uang terhadap stabilitas biaya ekspor/impor.
- **15% Inflation Risk:** Mengukur tingkat inflasi ekonomi nasional; inflasi tinggi akan memberikan penalti risiko.

### 2. Lexicon-based Sentiment Analysis
Sistem analisis sentimen dibangun secara native menggunakan PHP tanpa bergantung pada layanan NLP eksternal. Setiap teks berita akan dipecah (tokenized) dan dicocokkan dengan lebih dari 1.600 kata kunci positif dan negatif yang tersimpan dalam basis data lokal untuk menghasilkan skor sentimen yang akurat dan independen.

---

## Tech Stack & Integrasi API Eksternal

Sistem ini didukung oleh berbagai layanan API publik untuk meraup data global secara real-time:
1. **REST Countries API** - Data geografi dan batas wilayah
2. **World Bank API** - Indikator ekonomi makro (GDP, Populasi)
3. **Open-Meteo API** - Data cuaca real-time global
4. **ExchangeRate API** - Fluktuasi nilai tukar mata uang
5. **GNews API** - Agregator berita geopolitik global

---

## Panduan Instalasi (Local Development)

Untuk menjalankan proyek ini di lingkungan pengembangan lokal, ikuti instruksi berikut:

1. **Kloning Repositori:**
   ```bash
   git clone https://github.com/Username/tradesentry.git
   cd tradesentry
   ```

2. **Instalasi Dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Pastikan untuk mengonfigurasi pengaturan koneksi MySQL pada file `.env`.*

4. **Migrasi Database & Seeding:**
   Jalankan perintah berikut untuk membangun skema tabel dan mengisi data awal (termasuk kamus sentimen dan akun admin default):
   ```bash
   php artisan migrate --seed
   ```

5. **Menjalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Akses `http://127.0.0.1:8000` pada peramban web Anda.

---
*TradeSentry 2026 - Universitas Malikussaleh*
