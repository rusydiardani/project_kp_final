# 📋 Sistem Informasi Manajemen Pengambilan KTP (SIM-KTP)

## 💻 Arsitektur & Teknologi (Tech Stack)
Sistem Informasi KTP ini merupakan aplikasi _Full Stack_ murni yang dibangun dengan fondasi teknologi standar industri guna perawatan jangka panjang, yaitu:
- **Framework Utama:** Laravel (PHP 8.x)
- **Database:** MySQL
- **Frontend / UI:** HTML5, CSS3, JavaScript, Laravel Blade Templates & Custom CSS (Bootstrap/Tailwind)
- **Autentikasi:** Laravel Auth (Session-based)
- **Ekspor Data:** Maatwebsite Excel (untuk rekapitulasi pelaporan)

## 🚀 Fitur Lengkap Sistem

## Deskripsi Sistem Informasi Manajemen e-KTP

Sistem ini memiliki tiga aktor utama yaitu **Admin**, **Petugas**, dan **Penerima (Masyarakat)**. Namun, sistem hanya memiliki dua role internal yaitu **Admin** dan **Petugas**. Perbedaan utama antara keduanya adalah Admin memiliki hak akses tambahan seperti menghapus data dan mengelola akun pengguna, sedangkan Petugas hanya berfokus pada operasional layanan.

---

### 1. Autentikasi dan Role

Semua pengguna wajib melakukan login sebelum mengakses sistem. Setelah login, sistem akan melakukan validasi dan menentukan role pengguna (Admin atau Petugas). Berdasarkan role tersebut, pengguna akan diarahkan ke halaman dashboard.

---

### 2. Dashboard

Setelah berhasil login, pengguna akan masuk ke halaman dashboard yang menampilkan:

* Total KTP yang masuk
* Jumlah KTP yang sudah diambil
* Jumlah KTP yang belum diambil
* Grafik tren KTP yang diambil dan belum diambil dalam 7 hari terakhir
* Aktivitas sistem terbaru

---

### 3. Layanan (Manajemen Data KTP)

Pada halaman layanan, Petugas maupun Admin dapat:

* Menginput data KTP baru berupa **NIK dan Nama**

#### 🔐 Validasi NIK

* NIK harus terdiri dari **16 digit angka**
* NIK bersifat **unik (tidak boleh sama)**
* Jika NIK sudah terdaftar:

  * Sistem menampilkan pesan **“NIK sudah terdaftar / tidak boleh sama”**
  * Data gagal disimpan

---

#### Fitur Tabel Layanan:

* Menampilkan:

  * Nama Pemohon + NIK
  * Petugas Input
  * Tanggal Dicetak
  * Status (Belum Diambil / Sudah Diambil)

* Fitur:

  * Search & filter (status, tanggal, nama, NIK)
  * Checkbox (pilih data)
  * Select all

---

#### Aksi:

* **Edit data**

* **Proses ambil KTP**:

  * Petugas menginput **Nomor HP pengambil**
  * Sistem menentukan status pengambilan:

    * **YBS (Yang Bersangkutan)**
    * **Diwakilkan**

  ##### 📌 Ketentuan Pengambilan:

  * Jika **YBS**:

    * Tidak perlu input NIK tambahan

  * Jika **Diwakilkan**:

    * **Wajib menginput NIK perwakilan**
    * NIK harus:

      * 16 digit angka
      * Valid

  ##### ❗ Validasi:

  * Jika status diwakilkan tetapi NIK kosong:

    * Sistem menampilkan pesan:
      👉 *“NIK perwakilan wajib diisi”*

* Jika NIK tidak valid:

  * Sistem menampilkan error
  * Proses tidak dilanjutkan

* Jika semua valid:

  * Status diubah menjadi **Sudah Diambil**
  * Sistem menyimpan:

    * Nomor HP
    * Status (YBS / Diwakilkan)
    * NIK wakil (jika ada)
    * Petugas penyerah
    * Waktu pengambilan

- **Hapus data**:

  * Hanya untuk Admin
  * Mendukung:

    * Hapus satu data
    * Hapus banyak data (bulk delete)

---

### 4. Laporan Pengambilan

Halaman ini menampilkan data KTP yang telah diambil.

#### 📊 Struktur Tabel:

* Nama Pemohon + NIK
* Petugas Penyerah
* Tanggal & Jam Pengambilan
* Nomor Telepon Pengambil
* Status Pengambilan:

  * YBS
  * Diwakilkan
* NIK Wakil (jika ada)
* Aksi (hapus data)

---

#### 🔍 Fitur:

* Filter berdasarkan:

  * Rentang tanggal
  * Status (YBS / Diwakilkan / Semua)
  * Nama
  * NIK

* **Export ke Excel (fitur ini hanya tersedia pada halaman Laporan Pengambilan)**

* Checkbox:

  * Pilih satu atau beberapa data
  * Select all

---

#### ⚙️ Aksi:

* Hapus data laporan:

  * Hanya untuk **Admin**
  * Mendukung:

    * Hapus satu data
    * Hapus banyak data (bulk delete)

* Petugas:

  * Tidak dapat menghapus
  * Hanya melihat dan filter data

---

### 5. Manajemen Akun

Hanya Admin yang dapat:

* Menambah user
* Mengedit user
* Menghapus user

---

### 6. Edit Profil

Semua user dapat:

* Mengubah email
* Mengubah password
* Mengubah foto profil

---

### 7. Logout

Semua pengguna dapat keluar dari sistem.

---

### 8. Catatan Tambahan

* Semua fitur hanya dapat diakses setelah login
* Sistem mencatat aktivitas untuk audit
* Validasi data dilakukan untuk menjaga keakuratan sistem




**© Hak Cipta - Dokumentasi Pengembangan Proyek Kerja Praktik (KP).**
