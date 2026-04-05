# 📋 Sistem Informasi Manajemen Pengambilan KTP (SIM-KTP)

Sistem Informasi Manajemen Pengambilan KTP adalah aplikasi berbasis web yang dirancang khusus untuk memfasilitasi Dinas Kependudukan dan Pencatatan Sipil (Disdukcapil) dalam mengelola, melacak, dan mengaudit proses serah terima Kartu Tanda Penduduk (KTP) kepada warga masyarakat. Sistem ini memastikan transparansi, ketepatan waktu, dan akuntabilitas operasional.

## 💻 Arsitektur & Teknologi (Tech Stack)
- **Framework Utama:** Laravel (PHP)
- **Database:** MySQL
- **Frontend / UI:** Laravel Blade Templates & Custom CSS
- **Autentikasi:** Laravel Auth (Session-based)
- **Ekspor Data:** Maatwebsite Excel (untuk rekapitulasi pelaporan)

---

## 🚀 Fitur Lengkap Sistem

### 1. 🔐 Autentikasi & Keamanan Akses
- **Wajib Login:** Sistem bersifat privat dan *secure*, mewajibkan seluruh admin maupun petugas keamanan untuk *login* terlebih dahulu.
- **Registrasi Tertutup:** Fitur pendaftaran *public* (mandiri) dinonaktifkan untuk mencegah akses dari luar instansi. Akun baru hanya bisa dibuatkan melalui administrator.
- **Role-Based Access Control (RBAC):** Pemisahan hak akses serta fungsionalitas antarmuka yang sangat tegas antara peran **Admin** (akses seluruh menu) dan **Petugas** (operasional harian).

### 2. 📊 Dasbor & Monitoring (Real-time)
- **Welcome Banner & UI Modern:** Antarmuka dengan visualisasi sambutan yang interaktif dan *clean*.
- **Statistik Interaktif (Metrics):** Panel statistik *real-time* yang menampilkan angka akumulasi jumlah antrean KTP, KTP yang sudah selesai diserahkan, serta persentase rasio keberhasilan (Success Rate).
- **Log Aktivitas (Activity Feed):** Menampilkan pergerakan dan jejak riwayat pengambilan/antrean secara langsung (Terkini) di halaman utama sebagai audit visual instan.

### 3. 📂 Manajemen Antrean & Proses Serah Terima KTP (Layanan Utama)
- **Manajemen CRUD Operasional:** Memungkinkan pengisian data registrasi, perubahan informasi (Edit), serta penghapusan data tunggal (Delete) bagi dokumen KTP yang sedang diproses.
- **Validasi NIK Ketat & Unik:** 
  - Sistem mewajibkan input NIK dalam format tepat 16 digit angka.
  - Memiliki fitur pencegahan NIK ganda: menampilkan sistem peringatan ramah (*user-friendly alert*) ke petugas bila NIK yang sama masih tercatat di dalam antrean belum selesai.
- **Perhitungan Deadline Otomatis (SLA):** Sistem otomatis merumuskan *Service Level Agreement* (Tenggat Waktu) maksimal 5 hari kerja sejak tanggal KTP diajukan ke loket, dengan cerdas membedakan dan mengabaikan hari libur akhir pekan (Sabtu-Minggu).
- **Rekam Jejak & Audit Pengambilan (Pickup Logging):**
  - **Identitas Pelepas Berkas:** Menyimpan *ID/Nama Petugas* (`released_by`) yang secara faktual menyerahkan fisik KTP ke pengunjung, untuk kontrol internal.
  - **Opsi Faktual Pengambil:** Opsi spesifik status pengambilan jatuh kepada siapanya. Terbagi menjadi 2 pilihan:
    1. **YBS (Yang Bersangkutan):** Diambil langsung oleh pemilik e-KTP.
    2. **Perwakilan (Kuasa):** Diambil oleh orang lain, dimana sistem **mewajibkan input 16 Digit NIK Pengambil** sebagai alat ukur pertanggungjawaban.
- **Hapus Massal (Bulk Delete):** Fitur efisiensi administratif spesifik untuk membersihkan atau menghapus puluhan/ratusan *record* (data) sekaligus dengan 1x klik. 

### 4. 🖨️ Manajemen Pelaporan & Ekspor Format Tinggi (Smart Report)
- **Logika Filter Transparan:** Mengumpulkan data serah-terima KTP yang sudah *Done/Completed/YBS* maupun yang masih *Pending* dalam menu Laporan, difilter berdasarkan *Start Date*, *End Date*, maupun kategorisasi spesifik.
- **Fungsi Bulk Delete Laporan:** Admin dapat mengelola ukuran *database* dengan melaksanakan *Bulk Delete* catatan historis pada menu laporan pelacakan.
- **Custom Excel Export (Maatwebsite):**
  - Mengunduh rekap dalam file Ms. Excel berstandar format laporan Instansi/Pemerintahan (termasuk *header* tabel representatif).
  - Menggunakan struktur Tanggal/Waktu khas Indonesia (e.g. 15 Agustus 2026).
  - Melindungi integritas kolom angka ekstrim (Nomor NIK, Taker NIK, dan Nomor HP) untuk tidak melengkung menjadi format matematika ilmiah (*Scientific Format Notation*) maupun mencegah putusnya angka "0" di awal di aplikasi Excel.

### 5. 🧑‍💼 Administrasi Pengguna & Modifikasi Profil Tambahan
- **Admin-Only User Management:** Panel kontrol absolut dimana *hanya Admin* yang dapat melakukan intervensi penciptaan akun *User/Petugas* baru, mengubah level otorisasi akses (*role*), dan menonaktifkan pengguna.
- **Personalisasi Avatar/Profil:** Anggota/Petugas berhak menyunting data fundamental personalnya secara mandiri, yang mencakup perubahan Nama Lengkap, pembaruan kata sandi (*Password*), hingga penyematan foto profil (*Avatar*) kustom.

---

## 💡 Nilai Tambah & Tata Kelola
Sistem *SIM-KTP* dirancang tidak sebatas sebatas aplikasi pendataan, namun sebagai mesin pendukung integritas layanan internal (Disdukcapil). Manakala kelak terjadi keluhan/komplain dari publik mengenai "klaim" bahwa KTP belum diserahkan, institusi cukup mengecek data melalui riwayat sistem ini yang dapat melacak dengan presisi:
- *Kapan persisnya KTP tersebut keluar dari gudang instansi,* 
- *Siapa oknum pegawai/petugas internal yang melepas dokumen tersebut,* dan 
- *Kepada siapa entitas eksternalnya (Apakah YBS atau NIK orang/Kuasa yang mana) barang tersebut diberikan.*
