# Asumsi & Keputusan Desain — Sistem Reservasi & Pelaporan Fasilitas Kampus

Dokumen ini mencatat asumsi yang diambil tim ketika requirement tidak menjelaskan secara eksplisit
(sesuai instruksi soal: "Silakan tambahkan asumsi ataupun tabel/atribut pada rancangan data jika
diperlukan"). Setiap asumsi dicatat beserta alasan singkat supaya bisa dijelaskan saat sesi tanya
jawab presentasi.

---

## 1. Aturan Bisnis Reservasi

### 1.1 Definisi Bentrok Jadwal
**Asumsi:** Dua reservasi dianggap bentrok jika berada pada slot waktu yang sama, di fasilitas yang
sama. **Tidak ada buffer time** antar reservasi (mis. reservasi 07.00–07.30 dan 07.30–08.00 pada
fasilitas yang sama dianggap valid berdampingan, tanpa jeda persiapan).

**Implikasi teknis:** Validasi bentrok cukup mengecek overlap `facility_id` + rentang waktu, tanpa
perlu menambahkan margin waktu di query.

### 1.2 Batas Waktu Pembatalan Reservasi
**Asumsi:** Pengguna dapat membatalkan reservasi miliknya sendiri **maksimal H-1** (satu hari
sebelum tanggal reservasi). Setelah melewati batas ini, pembatalan hanya bisa dilakukan oleh
petugas (lihat US #10).

**Implikasi teknis:** Validasi di server membandingkan tanggal reservasi dengan tanggal saat ini
(`now()`); jika selisih kurang dari 1 hari, tombol batal dinonaktifkan di sisi pengguna dan request
ditolak di sisi server bila tetap dipaksakan.

### 1.3 Slot Waktu & Jam Operasional
**Asumsi:** Mengikuti ketentuan soal — jam operasional 07.00–20.00, slot tetap 30 menit.
Validasi `start_time` dan `end_time` wajib dilakukan di sisi server, bukan hanya dari input
kalender di frontend.

---

## 2. Status & Ketersediaan Fasilitas

### 2.1 Fasilitas "Dalam Perbaikan"
**Asumsi:** Fasilitas yang ditandai petugas sebagai "dalam perbaikan" akan **ditampilkan sebagai
"tidak tersedia"** di halaman ketersediaan yang dilihat pengunjung/pengguna (US #1), tanpa
membedakan status "tidak tersedia karena sudah dipesan" dengan "tidak tersedia karena perbaikan"
pada tampilan publik.

**Implikasi teknis:** Kolom `status` pada tabel `facilities` (enum: `aktif`, `dalam_perbaikan`,
`nonaktif`) dicek terpisah dari status reservasi per slot. Jika `status = dalam_perbaikan`, seluruh
slot fasilitas tersebut ditampilkan tidak tersedia terlepas dari ada/tidaknya reservasi aktif pada
slot tersebut.

### 2.2 Reservasi Aktif saat Fasilitas Ditandai Perbaikan
**Asumsi:** Reservasi yang sudah di-approve pada fasilitas yang kemudian ditandai "dalam perbaikan"
**tidak dibatalkan otomatis oleh sistem**. Petugas wajib membatalkan reservasi terdampak secara
manual satu per satu melalui fitur pembatalan darurat (US #10), dengan mengisi kolom alasan
pembatalan.

**Alasan:** Pembatalan manual memastikan setiap pembatalan tercatat dengan alasan eksplisit
(`cancelled_reason`) untuk kebutuhan audit, dan menghindari efek samping otomatis yang sulit
ditelusuri jika terjadi kesalahan penandaan status fasilitas oleh petugas.

---

## 3. Role & Hak Akses Pengguna

### 3.1 Kesetaraan Role Mahasiswa/Dosen/Staf
**Asumsi:** Ketiga jenis pengguna (mahasiswa, dosen, staf) diperlakukan **setara** dalam sistem —
tidak ada prioritas antrian, kuota berbeda, atau pembatasan akses fasilitas tertentu berdasarkan
jenis pengguna. Perbedaan hanya tercatat sebagai atribut informasi pada profil, bukan sebagai aturan
bisnis yang memengaruhi alur reservasi.

**Implikasi teknis:** Kolom `role` pada tabel `users` cukup dibedakan pada level `pengguna` /
`petugas` / `admin` untuk keperluan otorisasi. Jika status mahasiswa/dosen/staf ingin dicatat,
ditambahkan sebagai atribut deskriptif terpisah (mis. kolom `tipe_pengguna`), bukan sebagai bagian
dari logika permission.

### 3.2 Registrasi Mandiri Pengguna (US #15)
**Asumsi:** Registrasi mandiri pengguna **diimplementasikan**, bukan dilewati. Soal secara eksplisit
menuliskan "hasil registrasi mandiri (jika diimplementasikan)" pada US #15, yang dibaca tim sebagai
opsi yang sengaja dibuka dosen — kemungkinan untuk membedakan cakupan fitur antar kelompok. Karena
biaya implementasinya relatif rendah dibanding nilai tambahan cakupan user story saat presentasi,
tim memilih untuk mengimplementasikannya.

**Implikasi teknis:** Tambah kolom `status_akun` (enum: `pending`, `verified`, `rejected`) pada
tabel `users`. Akun yang didaftarkan langsung oleh admin (US #13/#14) otomatis berstatus
`verified`; akun hasil registrasi mandiri berstatus awal `pending` sampai disetujui/ditolak admin
(US #15). Login ditolak sistem selama status masih `pending`.

**Catatan risiko:** Jika mendekati deadline tim kekurangan waktu, keputusan ini valid untuk
dibatalkan (skip registrasi mandiri, semua akun didaftarkan admin) karena soal memang memberi opsi
eksplisit — tapi itu harus jadi keputusan sadar karena kendala waktu, bukan default karena belum
sempat dikerjakan.

---

## 4. Upload & Penyimpanan File

### 4.1 Foto Laporan Kerusakan
**Asumsi (mengikuti konvensi umum aplikasi web berbasis Laravel):**
- Format file diterima: JPEG, PNG
- Ukuran maksimal: 2 MB per file
- Disimpan di `storage/app/public` dengan path relatif dicatat di kolom `foto_path` pada tabel
  `reports` — bukan disimpan sebagai BLOB di database

**Alasan:** Menyimpan file sebagai BLOB memperberat ukuran database dan memperlambat query;
konvensi Laravel standar menggunakan filesystem disk + `php artisan storage:link`.

### 4.2 Laporan Kerusakan Aktif per Fasilitas
**Asumsi:** Satu fasilitas **dapat memiliki lebih dari satu laporan kerusakan aktif secara
bersamaan**, tanpa batasan jumlah dan tanpa constraint khusus di level aplikasi maupun database.

**Alasan:** Mengikuti pola umum sistem asset/facility management — dua laporan pada fasilitas yang
sama bisa membahas masalah yang sepenuhnya berbeda (mis. AC rusak vs proyektor rusak di ruang yang
sama), sehingga membatasi jumlah laporan aktif justru berisiko memblokir laporan valid yang tidak
terkait satu sama lain.

### 4.3 Pencatatan Petugas Penyelesai Laporan
**Asumsi:** Kolom `resolved_by` (foreign key ke `users`, nullable) **ditambahkan** pada tabel
`reports`, diisi otomatis dengan ID petugas yang mengubah status laporan menjadi
`selesai`/`ditolak`.

**Alasan:** Diperlukan untuk mendukung rekap lintas fasilitas oleh admin (US #17), khususnya untuk
keperluan audit — melacak petugas mana yang menangani laporan tertentu jika ada pertanyaan soal
kualitas resolusi.

---

## 5. Pencarian & Tampilan Data

### 5.1 Pencarian Fasilitas
**Asumsi:** Pencarian berdasarkan tipe/lokasi/kapasitas (US #2) dilakukan melalui filter query
**server-side** (form GET dengan parameter filter), bukan pencarian real-time berbasis AJAX/live
search. Hasil ditampilkan dengan pagination (disarankan 10–15 item per halaman).

**Alasan:** Sesuai scope tugas kuliah, kompleksitas AJAX search tidak diperlukan dan tidak
memengaruhi penilaian rubrik.

### 5.2 Format Export Rekap
**Asumsi:** Export rekap okupansi dan frekuensi kerusakan (US #17) dibuat dalam format **CSV** , **PDF**, dan **excel**.

**Alasan:**  Interpretasi teks soal *"mengekspor(CSV/Excel/PDF)"* dibaca sebagai **wajib mengimplementasikan ketiganya**.

---

## 6. Validasi Form

**Asumsi:** Validasi dilakukan di sisi server (wajib, menggunakan Laravel Form Request) **dan**
sisi client (HTML5 attributes minimal) untuk tiga form berikut, yang dianggap "form penting" sesuai
ketentuan umum soal:
1. Form registrasi pengguna
2. Form pengajuan reservasi
3. Form pelaporan kerusakan fasilitas

Form lain (mis. update profil, filter pencarian) tidak wajib validasi ganda karena tidak
memengaruhi integritas data inti sistem.

---

## 7. Sesi & Autentikasi

**Asumsi:** Menggunakan durasi sesi default Laravel (120 menit), tanpa kebutuhan khusus untuk
memperpanjang atau mempersingkat. Tidak ada mekanisme "remember me" otomatis kecuali ditambahkan
sebagai fitur tambahan di luar scope wajib.

---

## 8. Notifikasi

**Asumsi:** Sistem notifikasi terbatas pada **flash message** dalam aplikasi (banner sukses/gagal
setelah aksi seperti approve/reject/submit). Tidak ada notifikasi email atau push notification —
dianggap di luar scope tugas kuliah ini.

---

## 9. Konvensi Teknis Lintas Platform

**Asumsi:** Karena anggota tim menggunakan campuran macOS dan Windows, disepakati:
- Semua nama tabel dan kolom database menggunakan **snake_case, huruf kecil** (menghindari isu
  case-sensitivity MySQL yang berbeda default antara macOS/Linux dan Windows)
- Charset database: `utf8mb4` dengan collation `utf8mb4_unicode_ci`, dideklarasikan eksplisit di
  migration
- Skema database sumber kebenaran adalah **migration file Laravel** (bukan file `.sql` hasil
  export manual dari tools seperti Sequel Ace), untuk menghindari masalah kompatibilitas lintas OS
  dan menjaga version control yang konsisten
- Semua anggota tim menggunakan **MySQL versi 8.x** (tidak dicampur dengan versi 5.7) untuk
  menghindari perbedaan perilaku pada kolom `ENUM` dan `JSON`

---

## 10. Pertanyaan Terbuka (Belum Diklarifikasi)

Tidak ada pertanyaan terbuka tersisa terkait desain database saat ini. Satu poin yang masih layak
dikonfirmasi ke dosen (bukan diputuskan sendiri) adalah interpretasi format export pada 5.2 —
lihat catatan di subsection tersebut.

---

*Dokumen ini bersifat hidup (living document) — update setiap kali ada keputusan baru yang
memengaruhi asumsi atau membuka pertanyaan baru selama pengerjaan.*