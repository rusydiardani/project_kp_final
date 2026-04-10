# 📋 Sistem Informasi Manajemen Pengambilan KTP (SIM-KTP)

Sistem Informasi Manajemen Pengambilan KTP adalah aplikasi berbasis web yang dirancang khusus untuk memfasilitasi Dinas Kependudukan dan Pencatatan Sipil (Disdukcapil) dalam mengelola, melacak, dan mengaudit proses serah terima Kartu Tanda Penduduk (KTP) kepada warga masyarakat. Sistem ini memastikan transparansi, ketepatan waktu, dan akuntabilitas operasional.

---

## 📖 Latar Belakang Proyek
Pencatatan data pengajuan KTP-el serta pemantauan status pencetakannya masih dikerjakan secara manual menggunakan buku besar. Proses ini rentan terhadap kendala kelambatan pencarian arsip, kehilangan data pendukung, serta pelacakan progres pembuatan yang tidak efisien. 

Sistem ini diinisiasi untuk mengatasi masalah tersebut dengan mengembangkan sebuah pangkalan data **(database)** terpusat di mana pembaruan progres penerbitan dokumen KTP (seperti *'Sedang Diajukan'*, *'Tercetak'*, hingga *'Selesai'* ) dapat dikontrol dengan sangat instan dan akurat.

## 💻 Arsitektur & Teknologi (Tech Stack)
Sistem Informasi KTP ini merupakan aplikasi _Full Stack_ murni yang dibangun dengan fondasi teknologi standar industri guna perawatan jangka panjang, yaitu:
- **Framework Utama:** Laravel (PHP 8.x)
- **Database:** MySQL
- **Frontend / UI:** HTML5, CSS3, JavaScript, Laravel Blade Templates & Custom CSS (Bootstrap/Tailwind)
- **Autentikasi:** Laravel Auth (Session-based)
- **Ekspor Data:** Maatwebsite Excel (untuk rekapitulasi pelaporan)

---

## 🚀 Fitur Lengkap Sistem

### 1. 🔐 Autentikasi & Keamanan Akses
- **Wajib Login:** Sistem bersifat privat dan *secure*, mewajibkan seluruh admin maupun petugas keamanan untuk *login* terlebih dahulu.
- **Registrasi Tertutup:** Fitur pendaftaran *public* (mandiri) dinonaktifkan untuk mencegah akses dari luar instansi. Akun baru hanya bisa dibuatkan melalui administrator.
- **Role-Based Access Control (RBAC):** Pemisahan hak akses serta fungsionalitas antarmuka yang sangat tegas antara peran **Admin** (akses seluruh menu) dan **Petugas** (operasional harian). Transparansi 100% dimana semua riwayat penambahan atau pengubahan status e-KTP sinkron secara _real-time_ untuk bisa dilihat di antara pegawai.

### 2. 📊 Dasbor & Monitoring (Real-time)
- **Welcome Banner & UI Modern:** Antarmuka dengan visualisasi sambutan yang interaktif dan *clean*.
- **Statistik Interaktif (Metrics):** Panel statistik *real-time* yang menampilkan angka akumulasi jumlah antrean KTP, KTP yang sudah selesai diserahkan, serta persentase rasio keberhasilan (Success Rate).
- **Log Aktivitas (Activity Feed):** Menampilkan pergerakan dan jejak riwayat pengambilan/antrean secara langsung (Terkini) di halaman utama sebagai audit visual instan.

### 3. 📂 Manajemen Antrean & Proses Serah Terima KTP (Layanan Utama)
- **Manajemen CRUD Operasional:** Memungkinkan pengisian data registrasi, perubahan informasi (Edit), serta penghapusan data tunggal (Delete) bagi dokumen KTP yang sedang diproses. Modul formulir yang mempermudah petugas merangkum data krusial warga untuk disematkan dalam daftar antrean e-KTP.
- **Validasi NIK Ketat & Unik:** 
  - Sistem mewajibkan input NIK dalam format tepat 16 digit angka.
  - Memiliki fitur pencegahan NIK ganda: menampilkan sistem peringatan ramah (*user-friendly alert*) ke petugas bila NIK yang sama masih tercatat di dalam antrean belum selesai.
- **Rekam Jejak & Audit Pengambilan (Pickup Logging):**
  - **Identitas Pelepas Berkas:** Menyimpan *ID/Nama Petugas* (`released_by`) yang secara faktual menyerahkan fisik KTP ke pengunjung, untuk kontrol internal.
  - **Opsi Faktual Pengambil:** Opsi spesifik status pengambilan jatuh kepada siapanya. Terbagi menjadi 2 pilihan:
    1. **YBS (Yang Bersangkutan):** Diambil langsung oleh pemilik e-KTP.
    2. **Perwakilan (Kuasa):** Diambil oleh orang lain, dimana sistem **mewajibkan input 16 Digit NIK Pengambil** sebagai alat ukur pertanggungjawaban. Mengurangi _load_ halaman berkat mutasi responsif.
- **Hapus Massal (Bulk Delete):** Fitur efisiensi administratif spesifik untuk membersihkan atau menghapus puluhan/ratusan *record* (data) sekaligus dengan 1x klik. 

### 4. 🖨️ Manajemen Pelaporan & Ekspor Format Tinggi (Smart Report)
- **Logika Filter Transparan:** Menyaring data rekam jejak KTP yang sudah berhasil diserahkan (*Completed*) berdasarkan rentang waktu (*Start & End Date*). Dilengkapi dengan **Filter Spesifik Metode Pengambilan** untuk menyeleksi cepat mana KTP yang diambil langsung oleh pemilik (YBS) dan mana yang diserahkan ke pihak lain (Diwakilkan).
- **Fungsi Bulk Delete Laporan:** Admin dapat mengelola dan memangkas ukuran *database* dengan melaksanakan penghapusan massal (*Bulk Delete*) pada catatan historis pelacakan.
- **Custom Excel Export (Maatwebsite):**
  - Mengunduh rekap dalam file Ms. Excel berstandar format laporan Instansi/Pemerintahan (termasuk *header* tabel representatif).
  - Menggunakan struktur Tanggal/Waktu khas Indonesia (e.g. 15 Agustus 2026).
  - Melindungi integritas kolom angka ekstrim (Nomor NIK, Taker NIK, dan Nomor HP) untuk tidak melengkung menjadi format matematika ilmiah (*Scientific Format Notation*) maupun mencegah putusnya angka "0" di awal di aplikasi Excel.

### 5. 🧑‍💼 Administrasi Pengguna & Modifikasi Profil Tambahan
- **Admin-Only User Management:** Panel kontrol absolut dimana *hanya Admin* yang dapat melakukan intervensi penciptaan akun *User/Petugas* baru, mengubah level otorisasi akses (*role*), dan menonaktifkan pengguna.
- **Personalisasi Avatar/Profil:** Anggota/Petugas berhak menyunting data fundamental personalnya secara mandiri, yang mencakup perubahan Nama Lengkap, pembaruan kata sandi (*Password*), hingga penyematan foto profil (*Avatar*) kustom.

---

## ⚙️ Petunjuk Instalasi (Panduan _Clone_ & Jalankan di Lokal)

Jika penguji atau pengguna ingin mengimplementasikan langsung sistem ini (misalnya di _localhost_ via XAMPP / WAMP server), berikut adalah instruksi setup yang wajib diikuti:

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

6. **Migrasikan Struktur Tabel Database:**
   Bentuk _blueprint_ pangkalan data di server lokal agar semua _form_ berfungsi.
   ```bash
   php artisan migrate
   ```

7. **Jalankan Peladen Lokal Laravel (*Localhost*):**
   ```bash
   php artisan serve
   ```
   *Buka Peramban / Browser PC Anda, lalu kunjungi `http://localhost:8000`.*

---

## 💡 Nilai Tambah & Tata Kelola
Sistem *SIM-KTP* dirancang tidak sebatas aplikasi pendataan, namun sebagai mesin pendukung integritas layanan internal (Disdukcapil). Manakala kelak terjadi keluhan/komplain dari publik mengenai "klaim" bahwa KTP belum diserahkan, institusi cukup mengecek data melalui riwayat sistem ini yang dapat melacak dengan presisi:
- *Kapan persisnya KTP tersebut keluar dari gudang instansi,* 
- *Siapa oknum pegawai/petugas internal yang melepas dokumen tersebut,* dan 
- *Kepada siapa entitas eksternalnya (Apakah YBS atau NIK orang/Kuasa yang mana) barang tersebut diberikan.*

**© Hak Cipta - Dokumentasi Pengembangan Proyek Kerja Praktik (KP).**
