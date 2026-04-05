# 🪪 Sistem Informasi Pencatatan & Pelacakan KTP Elektronik (Disdukcapil)

Proyek ini merupakan **Sistem Informasi Berbasis Web** yang dirancang sebagai tugas akhir / laporan Kerja Praktik (KP). Aplikasi ini dibuat khusus untuk memodernisasi dan mendigitalkan alur pencatatan serta manajemen status pembuatan Kartu Tanda Penduduk Elektronik (KTP-el) di lingkungan Dinas Kependudukan dan Pencatatan Sipil (Disdukcapil).

---

## 📖 Latar Belakang Proyek
Pencatatan data pengajuan KTP-el serta pemantauan status pencetakannya masih dikerjakan secara manual menggunakan buku besar. Proses ini rentan terhadap kendala kelambatan pencarian arsip, kehilangan data pendukung, serta pelacakan progres pembuatan yang tidak efisien. 

Sistem ini diinisiasi untuk mengatasi masalah tersebut dengan mengembangkan sebuah pangkalan data **(database)** terpusat di mana pembaruan progres penerbitan dokumen KTP (seperti *'Sedang Diajukan'*, *'Tercetak'*, hingga *'Selesai'* ) dapat dikontrol dengan sangat instan dan akurat.

## 🌟 Nilai Utama (*Core Value*)
Hal yang menjadi sorotan utama dari arsitektur aplikasi ini adalah perombakan **Manajemen Hak Akses Bekerja Secara Kolaboratif & Transparan**. 
Aplikasi ini membagi *user* ke dalam peran `Petugas` (di garis depan) dan `Admin` (pengelola). Alih-alih menyekat data sehingga satu petugas tidak bisa melihat hasil inputan pekerja lainnya, sistem ini dibuat **100% transparan**. Semua riwayat penambahan atau pengubahan status e-KTP sinkron secara _real-time_ untuk bisa dilihat dan diedit bersama-sama, sehingga meminimalkan redundansi/penumpukan pencatatan arsip di lapangan.

---

## 🚀 Fitur Utama Sistem

1. **Dashboard Statistik** 📊
   Pusat kendali antarmuka (*user interface*) yang ramah untuk melihat ringkasan berapa banyak e-KTP yang sedang terdaftar, diproses, atau yang sudah siap distribusi.

2. **Otentikasi & Keamanan Sesi (*Login System*)** 🔐
   Mencegah peretasan atau manipulasi bebas; hanya pihak berwenang (`Admin` dan `Petugas` yang sudah diregistrasi) yang memiliki hak membuka menu sistem di peladen Disdukcapil.

3. **Modul Formulir Perekaman e-KTP Baru** 📝
   Antarmuka ringkas yang mempermudah petugas pencatatan merangkum data krusial warga untuk disematkan dalam daftar antrean e-KTP.

4. **Tabel Tracking & Update Status Progres** 🔄
   Logika operasional cerdas di mana suatu *ID KTP* dapat dilacak statusnya secara langsung, dengan tombol responsif untuk me-mutasi status *(misalnya masyarakat yang KTP-nya sudah selesai dicetak)* tanpa *load* halaman yang lama. 

---

## 🛠️ Stack Teknologi (Tech Stack)

Sistem Informasi KTP ini merupakan aplikasi _Full Stack_ murni yang dibangun dengan fondasi teknologi standar industri guna perawaran jangka panjang, yaitu:

*   **Penyedia Backend & API:** 
    *   **PHP (Versi 8.x)**
    *   **Laravel Framework**: Digunakan untuk arsitektur pengamanan _routing_ dan struktur modul MVP.
*   **Pengelola Database:** 
    *   **MySQL**: Relasi database terstruktur.
*   **Pengembang Frontend UI/UX:** 
    *   **HTML5 & CSS3**
    *   **JavaScript & AJAX**: Digunakan untuk rendering dan operasional transisi yang mulus.
    *   *(Bootstrap/Tailwind)*: Gaya _styling_ desain antarmuka.

---

## ⚙️ Petunjuk Instalasi (Panduan _Clone_ & Jalankan di Lokal)

Jika penguji atau pengguna ingin mengimplementasikan langsung sistem ini (misalnya di _localhost_ via XAMPP / WAMP server), berikut adakah instruksi setup yang wajib diikuti:

1. **Unduh Repositori via Git Clone:**
   ```bash
   git clone https://github.com/rusydiardani/project_kp_final.git
   ```

2. **Masuk ke Direktori Proyek Utama:**
   ```bash
   cd project_kp_final
   ```

3. **Pasang Instalasi Paket Dependensi Vendor:**
   Gunakan _Composer_ di terminal.
   ```bash
   composer install
   ```

4. **Siapkan Pengaturan Konfigurasi Lingkungan (`.env`):**
   Salin berkas struktur contoh lalu beri nama `.env`, dan sinkronkan dengan *username* serta *database* MySQL lokal milik Anda.
   ```bash
   cp .env.example .env
   ```

5. **Bangun Ulang Kunci Otentikasi Enkripsi:**
   ```bash
   php artisan key:generate
   ```

6. **Migrasikan Struktur Tabel Databse:**
   Bentuk _blueprint_ pangkalan data di server lokal agar semua _form_ berfungsi.
   ```bash
   php artisan migrate
   ```

7. **Jalankan Peladen Lokal Lararvel (*Localhost*):**
   ```bash
   php artisan serve
   ```
   *Buka Peramban / Browser PC Anda, lalu kunjungi `http://localhost:8000`.*

---

**© Hak Cipta - Dokumentasi Pengembangan Proyek Kerja Praktik (KP).**
