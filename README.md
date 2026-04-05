# Sistem Informasi Manajemen Pengambilan KTP (Project KP Disdukcapil)

Sistem Informasi Manajemen Pengambilan KTP adalah aplikasi berbasis web yang dirancang khusus untuk memfasilitasi Dinas Kependudukan dan Pencatatan Sipil (Disdukcapil) dalam mengelola, melacak, dan mengaudit proses serah terima Kartu Tanda Penduduk (KTP) kepada warga.

## 💻 Arsitektur & Tech Stack
- **Framework Utama:** Laravel (PHP)
- **Database:** MySQL
- **Frontend / UI:** Laravel Blade Templates & Custom CSS
- **Autentikasi:** Laravel Auth (Session-based)
- **Ekspor Data:** Maatwebsite Excel (untuk rekapitulasi)

## 🚀 Fitur Utama

### 1. Autentikasi & Otorisasi Ketat
- **Login Petugas/Admin:** Wajib login untuk akses sistem.
- **Registrasi Tertutup:** Pendaftaran mandiri dinonaktifkan untuk menjaga keamanan data; hanya Admin yang dapat menambahkan pengguna.
- **Role-Based Access Control:** Pembagian hak akses tegas antara `Admin` dan `Petugas`.

### 2. Dasbor Utama
- **Welcome Banner & Statistik:** Menampilkan sapaan dan perhitungan *real-time* jumlah antrean KTP, KTP yang sudah selesai, dan persentase keberhasilan.
- **Log Aktivitas:** Pantauan pergerakan antrean terbaru secara langsung di halaman depan.

### 3. Manajemen Antrean & Serah Terima KTP
- **Cegah NIK Ganda:** Sistem secara otomatis memvalidasi keunikan NIK (16 digit), menampilkan peringatan jika NIK sudah ada dalam antrean.
- **Perhitungan Deadline Otomatis:** Menambahkan target SLA 5 hari kerja secara otomatis saat pendaftaran (mengabaikan akhir pekan).
- **Audit Pengambilan KTP:** 
  - Mencatat opsi pengambilan: **Oleh YBS (Yang Bersangkutan)** atau **Perwakilan (Kuasa)**.
  - Wajib input NIK Pengambil jika dikuasakan.
  - Mencatat *ID Petugas* yang melepaskan dokumen (`released_by`) untuk pertanggungjawaban.
- **Bulk Delete:** Fitur khusus admin untuk menghapus banyak data seklaigus dalam satu klik.

### 4. Pelaporan & Ekspor Excel (Smart Export)
- Filter laporan berdasarkan rentang waktu (*Start/End Date*) dan Status (*Pending/Completed*).
- File Excel terformat rapi sesuai form pelaporan standar instansi (tanggal berformat Indonesia, header lengkap).
- Angka NIK dan Nomor HP terlindungi dari *Scientific Format Notation* atau pemotongan angka nol di Ms. Excel.

### 5. Manajemen Pengguna & Profil
- **Admin Only CRUD:** Admin dapat membuat, mengubah *role*, dan menghapus akses petugas.
- **Avatar/Profile Picture:** Petugas dapat mengubah detail nama, password, dan foto profil mereka.

## 💡 Nilai Tata Kelola
Aplikasi ini menjawab kebutuhan transparansi dan audit operasional Disdukcapil. Jika terdapat klaim warga yang merasa KTP-nya belum diserahkan, sistem dapat melacak secara akurat *kapan* KTP diambil, *siapa petugas* yang memberikan, dan *siapa pihak* yang mengambilnya (baik YBS langsung maupun kuasa).
