# Analisis Edge Case Sistem Reservasi & Pelaporan Fasilitas Kampus (Reserve Report)

Dokumen ini memetakan seluruh *edge case* yang relevan dengan arsitektur dan ruang lingkup aplikasi **Reserve Report** (Laravel Monolith MVC, Blade, Tailwind CSS, MySQL/SQLite).
*Edge case* yang berada di luar ruang lingkup (seperti perangkat keras scanner QR, arsitektur microservices terdistribusi, push notification pihak ketiga, dan aplikasi mobile native) telah dieliminasi agar analisis terfokus pada keandalan sistem aktual.

---

## 1. Waktu & Durasi Reservasi
- [x] **Booking tepat pada jam mulai (misal: 10:00–11:00):** Ditangani. Validasi slot 30 menit pada `StoreReservationRequest` menerima format `H:i` kelipatan 30 menit.
- [x] **Booking tepat pada batas waktu/boundary (misal: A 10:00–11:00 dan B 11:00–12:00):** Ditangani. Sesuai `ASSUMPTION.md` 1.1, sistem memperbolehkan peminjaman berdampingan (*back-to-back*) tanpa *buffer time*. Pengecekan overlap menggunakan operator `<` dan `>` (`start_time < existing_end` AND `end_time > existing_start`), sehingga batas waktu yang bersentuhan tidak memicu konflik.
- [x] **Start time sama dengan end time:** Ditangani. Divalidasi oleh `StoreReservationRequest` dengan aturan `'end_time' => ['after:start_time']`.
- [x] **End time lebih kecil dari start time:** Ditangani. Divalidasi oleh `StoreReservationRequest` dengan aturan `'end_time' => ['after:start_time']`.
- [x] **Booking melewati batas jam operasional:** Ditangani. `start_time` divalidasi `after_or_equal:07:00` dan `before:20:00`; `end_time` divalidasi `before_or_equal:20:00`.
- [x] **Booking melewati tengah malam / lintas hari:** Ditangani. Input tanggal berupa `date` tunggal dan jam operasional dibatasi maksimal pukul 20.00 WIB pada hari yang sama.
- [x] **Booking pada tanggal yang sudah lewat:** Ditangani. Aturan `'tanggal' => ['required', 'date', 'after_or_equal:today']` pada `StoreReservationRequest` mencegah pemilihan tanggal lampau.
- [x] **User memilih menit yang tidak sesuai interval slot 30 menit:** Ditangani. Closure validasi pada `StoreReservationRequest` memastikan menit hanya `:00` atau `:30`. Di frontend, dropdown hanya menyediakan slot 30 menit.
- [x] **Durasi booking terlalu pendek (< 30 menit):** Ditangani. Karena jam selesai harus `after:start_time` dan keduanya kelipatan 30 menit, durasi minimum otomatis 30 menit.
- [x] **Booking pada tanggal kabisat (29 Februari):** Ditangani. Tipe data kolom MySQL `DATE` dan PHP `DateTime` menangani tahun kabisat secara *native*.
- [x] **Perbedaan timezone antara client dan server:** Ditangani. Aplikasi menetapkan timezone aplikasi secara konsisten (`Asia/Jakarta` / WIB) dan label antarmuka secara eksplisit menampilkan format WIB.
- [ ] **Booking untuk hari ini tetapi jam mulai sudah terlewat:** Validasi tanggal mengizinkan `today`, namun jam mulai belum divalidasi terhadap `now()->format('H:i')` jika tanggal yang dipilih adalah hari ini. *(Saran: tambahkan rule komparasi waktu saat tanggal = hari ini).*
- [ ] **Booking terlalu jauh ke masa depan (misal: 2 tahun ke depan):** Belum ada batasan maksimum tanggal ke depan. *(Saran: tambahkan rule `before_or_equal:+60 days`).*
- [ ] **Durasi booking terlalu panjang (misal: meminjam seharian penuh 13 jam):** Saat ini diizinkan selama dalam rentang 07.00–20.00. *(Saran: tambahkan batasan maksimal durasi sekali pinjam, misal 4–6 jam).*

---

## 2. Konflik Jadwal & Konkurensi (Concurrency)
- [x] **Dua user melihat slot kosong lalu mengajukan permohonan bersamaan:** Ditangani. Pengajuan masuk berstatus `pending`. Ketika petugas menyetujui salah satu permohonan, sistem mengeksekusi `DB::transaction` dengan `lockForUpdate()` dan secara otomatis menolak (*auto-reject*) permohonan `pending` lain yang jadwalnya bertabrakan (`Petugas\ReservationController@approve`).
- [x] **User mengajukan permohonan pada slot yang sudah disetujui (*approved*):** Ditangani. `ReservationController@store` memverifikasi bentrok jadwal terhadap reservasi berstatus `approved` sebelum menyimpan ke database.
- [x] **Frontend memfilter jam ketersediaan secara dinamis:** Ditangani. Skrip `reservation-schedule.js` memanggil endpoint `/facilities/{facility}/schedule?date=...` secara asinkron, hanya menampilkan slot kosong, serta membatasi pilihan jam selesai agar tidak menabrak booking berikutnya.
- [x] **User melakukan double-click atau spam tombol kirim:** Ditangani. Tombol submit dinonaktifkan (`disabled`) oleh JavaScript saat proses pengiriman/loading, dan validasi server mencegah duplikasi jadwal yang disetujui.
- [x] **Stale network response saat user mengganti tanggal/fasilitas dengan cepat:** Ditangani. `reservation-schedule.js` mengimplementasikan counter `requestId` untuk mengabaikan respon jaringan yang datang terlambat.
- [x] **Dua petugas menyetujui reservasi yang sama secara bersamaan:** Ditangani. Proteksi database locking (`lockForUpdate()`) memastikan hanya transaksi pertama yang berhasil; transaksi kedua membatalkan aksi dengan pesan error.
- [x] **User membatalkan reservasi saat petugas sedang melakukan approval:** Ditangani. Status reservasi dikunci dalam transaksi database, mencegah transisi status yang inkonsisten.
- [x] **User menekan tombol Back lalu submit ulang:** Ditangani. Pola *Post/Redirect/Get* (PRG) dan validasi token CSRF mencegah *re-submission* data formulir yang sama.

---

## 3. Transisi Status Reservasi & Pembatalan
- [x] **Reservasi dibuat dengan status awal PENDING:** Ditangani. Nilai default kolom database dan controller memastikan status awal adalah `pending`.
- [x] **Persetujuan (Pending → Approved):** Ditangani. Petugas dapat menyetujui jadwal melalui antrean reservasi (`Petugas\ReservationController@approve`).
- [x] **Penolakan oleh Petugas (Pending → Rejected):** Ditangani. Petugas dapat menolak permohonan dengan menyertakan alasan penolakan (`cancelled_reason`).
- [x] **Pembatalan mandiri oleh Pemohon (US 4 & ASSUMPTION.md 1.2):** Ditangani. Pemohon hanya dapat membatalkan reservasi miliknya sendiri maksimal **H-1** sebelum tanggal penggunaan (`ReservationController@cancel`).
- [x] **Pemohon mencoba membatalkan pada hari-H atau setelah jadwal lewat:** Ditangani. Server menolak pembatalan jika `tanggal <= now()->toDateString()`.
- [x] **Pembatalan Darurat oleh Petugas (Approved → Cancelled, US 10):** Ditangani. Petugas dapat membatalkan reservasi yang sudah disetujui jika terjadi kondisi darurat/pemeliharaan mendadak via `emergencyCancel()` dengan alasan wajib diisi minimal 10 karakter.
- [x] **User mencoba membatalkan reservasi yang sudah ditolak atau dibatalkan:** Ditangani. Controller memvalidasi `in_array($reservation->status, ['pending', 'approved'])` sebelum memproses pembatalan.
- [x] **Transisi status ilegal langsung via request API (misal: Approved → Pending):** Ditangani. Tidak ada rute mutasi status generik; seluruh perubahan status dibatasi pada aksi spesifik (`approve`, `reject`, `cancel`, `emergencyCancel`).
- [ ] **Pembersihan reservasi PENDING yang sudah lewat tanggalnya:** Permohonan berstatus `pending` yang tidak sempat diproses petugas hingga hari-H terlewat masih tersimpan sebagai `pending`. *(Saran: buat artisan command / scheduled task untuk menandai permohonan kedaluwarsa menjadi expired/rejected).*

---

## 4. Kondisi Fasilitas & Pemeliharaan (Maintenance)
- [x] **Booking fasilitas yang sedang berstatus dalam perbaikan atau nonaktif:** Ditangani. `ReservationController@store` menolak pengajuan jika status fasilitas bukan `aktif`.
- [x] **Fasilitas ditandai "dalam_perbaikan" saat sudah ada reservasi yang disetujui:** Ditangani. Sesuai `ASSUMPTION.md` 2.2, sistem tidak membatalkan reservasi secara otomatis guna mencegah aksi sampingan tanpa jejak audit. Petugas membatalkan jadwal terdampak satu per satu menggunakan fitur pembatalan darurat (*emergency cancel*) dengan alasan eksplisit.
- [x] **Katalog fasilitas menyembunyikan fasilitas nonaktif:** Ditangani. Tampilan publik dan pengguna memfilter fasilitas nonaktif agar tidak dapat diakses atau diajukan peminjaman.
- [x] **Petugas memperbarui status laporan kerusakan sekaligus mengubah status fasilitas (US 12):** Ditangani. Form resolusi kerusakan pada `Petugas\ReportController@update` menyediakan opsi untuk langsung mengubah status operasional fasilitas (misal: menjadi `dalam_perbaikan` atau kembali `aktif`).

---

## 5. Autentikasi, Hak Akses & Keamanan
- [x] **Pengguna belum login mengakses fitur reservasi / pelaporan:** Ditangani. Middleware `auth` mengalihkan pengguna ke halaman login.
- [x] **Pengguna biasa (mahasiswa/dosen/staf) mengakses panel Admin atau Petugas:** Ditangani. Middleware `EnsureUserRole` memeriksa kecocokan role dan mengembalikan HTTP 403 (*Forbidden*) jika tidak berhak.
- [x] **Akun baru hasil registrasi mandiri langsung login:** Ditangani. Akun hasil registrasi mandiri berstatus `pending` (`ASSUMPTION.md` 3.2). `LoginController` memverifikasi status akun dan menolak autentikasi sebelum disetujui oleh Admin.
- [x] **Manipulasi kepemilikan data (IDOR - Insecure Direct Object References):** Ditangani:
  - Pembatalan reservasi memeriksa kepemilikan: `$reservation->user_id === $request->user()->id`.
  - Riwayat peminjaman dan riwayat laporan kerusakan difilter ketat berdasarkan `auth()->id()`.
  - ID pemohon (`user_id`) selalu diambil dari sesi server (`auth()->id()`), bukan dari payload request.
- [x] **Mass Assignment / Manipulasi role via payload request:** Ditangani. Atribut model dilindungi oleh `$fillable`, dan pembuatan user diverifikasi secara eksplisit di controller.
- [x] **Serangan Cross-Site Request Forgery (CSRF):** Ditangani. Middleware `VerifyCsrfToken` aktif pada seluruh rute POST/PATCH/DELETE.
- [x] **Serangan SQL Injection:** Ditangani. Seluruh interaksi database menggunakan Laravel Eloquent ORM dan PDO prepared statements dengan parameter binding.
- [x] **Serangan Cross-Site Scripting (XSS):** Ditangani. Blade template melakukan sanitasi dan *HTML-escaping* otomatis pada seluruh pencetakan variabel (`{{ ... }}`).
- [ ] **Sesi pengguna dinonaktifkan / ditolak oleh Admin saat user sedang aktif login:** Status akun dicek saat proses login. Jika akun di-reject di tengah sesi, pengguna masih bisa menavigasi sampai sesinya habis. *(Saran: tambahkan pengecekan `status_akun === 'verified'` pada middleware).*
- [ ] **Rate Limiting pada endpoint pengajuan reservasi dan pelaporan:** Endpoint login sudah memiliki proteksi throttle, tetapi endpoint POST reservasi dan laporan belum memiliki limit khusus. *(Saran: terapkan middleware `throttle:10,1`).*

---

## 6. Validasi Input & Berkas Foto Pelaporan Kerusakan
- [x] **Deskripsi permohonan atau laporan kosong atau hanya berisi whitespace:** Ditangani. Laravel `TrimStrings` membersihkan spasi, dan validasi mewajibkan minimal 5 karakter (`min:5`).
- [x] **Deskripsi laporan melebihi kapasitas database:** Ditangani. `StoreReportRequest` membatasi panjang deskripsi maksimal 2000 karakter dan dilengkapi penghitung karakter interaktif di antarmuka.
- [x] **Kategori laporan kerusakan tidak sesuai opsi:** Ditangani. Divalidasi oleh aturan `'category' => ['required', 'in:kerusakan,kebersihan,lainnya']`.
- [x] **Unggah berkas bukan gambar (misal: script PHP, executable, HTML):** Ditangani. `StoreReportRequest` memvalidasi `'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png']`.
- [x] **Unggah foto melebihi ukuran batas 2 MB:** Ditangani. Divalidasi oleh aturan `'photo' => ['max:2048']` dan dicek langsung di sisi client sebelum submit.
- [x] **Nama file mengandung path traversal (`../`) atau karakter berbahaya:** Ditangani. Penyimpanan file menggunakan metode bawaan Laravel `$request->file('photo')->store('reports', 'public')` yang menghasilkan hash acak unik dan mengabaikan nama asli file dari client.
- [x] **Dua pengguna mengunggah file dengan nama yang sama secara bersamaan:** Ditangani. Penamaan file berbasis hash acak menjamin tidak akan terjadi tabrakan nama file (*collision*).
- [x] **Satu fasilitas memiliki beberapa laporan kerusakan aktif sekaligus:** Ditangani. Sesuai `ASSUMPTION.md` 4.2, sistem memperbolehkan multi-laporan aktif pada satu fasilitas (misal: AC rusak dan proyektor rusak dilaporkan terpisah).

---

## 7. Integritas Data & Siklus Hidup (Lifecycle) Master Data
- [x] **Penghapusan fasilitas yang masih memiliki riwayat reservasi:** Ditangani. Foreign key pada tabel reservasi menggunakan `restrictOnDelete()`. Selain itu, `Admin\FacilityController@destroy` memeriksa keberadaan reservasi dan memblokir penghapusan, menyarankan admin untuk mengubah status fasilitas menjadi `nonaktif`.
- [x] **Penghapusan akun user yang memiliki histori reservasi / laporan:** Ditangani. Foreign key `user_id` dikonfigurasi dengan `restrictOnDelete()`, mencegah hilangnya jejak audit pengguna.
- [x] **Penghapusan akun petugas yang pernah memproses permohonan/laporan:** Ditangani. Foreign key `processed_by` dan `resolved_by` menggunakan `nullOnDelete()`, sehingga histori status tetap utuh meskipun akun petugas dihapus.
- [x] **Kapasitas fasilitas diisi angka negatif atau bukan angka:** Ditangani. `Admin\FacilityRequest` memvalidasi kapasitas berupa `integer|min:1`.

---

## 8. Rekapitulasi & Pelaporan Statistik (US 17)
- [x] **Reservasi yang ditolak (*rejected*) atau dibatalkan (*cancelled*) ikut terhitung sebagai okupansi:** Ditangani. Penghitungan jam okupansi pada `Admin\RekapController` memfilter ketat hanya reservasi dengan status `approved`.
- [x] **Ekspor rekapitulasi data ke berbagai format:** Ditangani. Sesuai `ASSUMPTION.md` 5.2, tersedia rute dan fitur ekspor lengkap untuk **CSV**, **Excel**, dan **Print PDF**.
- [x] **Penghitungan frekuensi laporan kerusakan per fasilitas:** Ditangani. Aggregator rekapitulasi mengelompokkan laporan berdasarkan fasilitas, status, dan kategori secara akurat.

---

## 9. Prinsip Arsitektur Utama yang Terpenuhi
1. **Database sebagai Single Source of Truth:** Seluruh pengecekan ketersediaan dan konflik jadwal dievaluasi langsung terhadap database.
2. **Validasi Ganda (Client-side & Server-side):** Antarmuka responsif memberikan umpan balik langsung, namun integritas data selalu dijaga penuh oleh FormRequest di backend.
3. **Audit Trail Lengkap:** Setiap perubahan status mencatat ID pemroses (`processed_by`, `resolved_by`), alasan pembatalan/penolakan (`cancelled_reason`), dan catatan resolusi teknisi.
4. **Proteksi Concurrency:** Penggunaan transaksi database (`DB::transaction`) dan *row-level locking* (`lockForUpdate()`) mencegah *race conditions* saat persetujuan permohonan.