# 🌍 Global Supply Chain Risk Intelligence Platform

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**TradeSentry** adalah sebuah platform pemantauan rantai pasok global (Supply Chain Monitoring) terpadu yang dirancang untuk mengukur, melacak, dan memvisualisasikan risiko perdagangan internasional secara *real-time*. Proyek ini dibangun untuk memenuhi kriteria Ujian Akhir Semester (UAS) Pemrograman Web.

> **Dosen Pengampu:** Muhammad Ikhwani, S.Pd.I., M.Sc  
> **Dikembangkan Oleh:** Azzam Shamil Basayef (NIM: 240180159)

---

## ✨ Fitur Utama

1. **🗺️ Live Operations Dashboard**  
   Peta interaktif (menggunakan *Leaflet.js*) yang memetakan lebih dari 140 negara beserta jalur pelayaran logistik antar pelabuhan (*Port Datasets*).
2. **⚠️ Risk Scoring Engine**  
   Mesin algoritma kustom yang menghitung skor risiko sebuah negara (0-100) berdasarkan komposit dari faktor Cuaca, Inflasi, dan Sentimen Berita Geopolitik.
3. **📰 News Intelligence & Sentiment Analysis**  
   Menarik berita terbaru secara *real-time* dan melakukan analisis sentimen (Positif/Negatif/Netral) menggunakan *Lexicon-based approach* dengan kamus kata kustom berbahasa PHP.
4. **📊 Country Comparison Engine**  
   Membandingkan data indikator ekonomi makro (GDP, Populasi, Ekspor-Impor, Inflasi) antar dua negara secara *head-to-head* tanpa *loading* halaman (AJAX).
5. **⭐ Watchlist System (Favorite)**  
   Simpan negara prioritas pantauan ke dalam dasbor eksklusif dengan teknologi integrasi *seamless*.
6. **🛡️ Admin Command Center (RBAC)**  
   Dasbor terpusat berbasis *Single-Page Tabular* untuk memanajemen *User*, *Port Datasets*, dan *Internal Articles* khusus untuk akun bersertifikasi Admin.
7. **🔌 Interactive API Documentation**  
   Portal referensi API mandiri yang menjelaskan secara detail seluruh *endpoint* REST API yang menggerakkan sistem AJAX di belakang layar.

---

## 🧠 The "Secret Sauce" (Algoritma Kustom)

Untuk menghindari sistem yang generik, proyek ini mengandalkan dua algoritma kustom utama:

### 1. Weighted Risk Scoring Algorithm
Skor risiko suatu wilayah tidak ditarik mentah-mentah dari API eksternal, melainkan dihitung secara mandiri melalui pembobotan (*weights*):
- **40% Sentimen Berita:** Berita negatif mendongkrak risiko secara eksponensial.
- **35% Inflasi Ekonomi:** Tingkat inflasi di atas dua digit memberikan penalti risiko.
- **25% Cuaca Ekstrem:** Suhu ekstrem atau kecepatan angin tinggi (*Typhoon/Hurricane risk*) menambah poin persentase risiko logistik pelabuhan.

### 2. Lexicon-based Sentiment Analysis
Sistem tidak menggunakan layanan AI eksternal yang berbayar (seperti OpenAI/Google Cloud) untuk analisis sentimen, melainkan mengandalkan **PHP Lexicon Dictionary** buatan sendiri. 
Setiap judul dan ringkasan berita akan dipecah (*tokenize*) dan dicocokkan dengan puluhan kata kunci positif (misal: *growth, agreement, peace*) dan negatif (misal: *war, crisis, strike, delay*) yang tersimpan dalam *database*, untuk menghasilkan kalkulasi sentimen yang *native* dan mandiri.

---

## 🛠️ Tech Stack & Integrasi API Eksternal

Sistem ini didukung oleh 6 API Publik (Gratis / No-Key / Basic Tier) untuk meraup data global:
1. **REST Countries API** - (Data geografi & batas wilayah)
2. **World Bank API** - (Indikator ekonomi, GDP, Populasi, Ekspor/Impor)
3. **Open-Meteo API** - (Data cuaca *real-time* bebas *API Key*)
4. **ExchangeRate API** - (Fluktuasi nilai tukar mata uang)
5. **World Port Index (Dataset)** - (Koordinat maritim dan pelabuhan dunia)
6. **GNews API** - (Agregator berita geopolitik dan logistik global)

---

## 🚀 Panduan Instalasi (Local Development)

Jika Anda ingin menjalankan proyek ini secara lokal, ikuti langkah-langkah berikut:

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
   *(Pastikan mengkonfigurasi koneksi MySQL Anda di dalam file `.env`)*

4. **Migrasi Database & Seeding (Penting):**
   Sistem ini sangat bergantung pada struktur *database* yang kompleks dan relasional.
   ```bash
   php artisan migrate --seed
   ```
   *(Perintah ini akan membuat semua 15+ tabel yang dibutuhkan beserta dengan akun Administrator default dan kamus Sentiment Analysis).*

5. **Jalankan Server:**
   ```bash
   php artisan serve
   ```
   Akses `http://127.0.0.1:8000` di peramban (browser) Anda.

---

> *"Supply Chain is like nature, it is all around us."*  
> **— TradeSentry 2026**
