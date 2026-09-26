# FacilityHub Design System & UI Style Guide (DESIGN.md)

Dokumen ini merupakan panduan acuan tunggal (*Single Source of Truth*) untuk desain antarmuka aplikasi **FacilityHub** (Sistem Reservasi & Pelaporan Fasilitas Kampus). Seluruh komponen antarmuka—mulai dari modul autentikasi bertema *Soft Frosted Glassmorphism*, katalog publik fasilitas, panel operasional petugas, hingga dashboard administratif—wajib mematuhi standarisasi ini.

---

## 1. Prinsip Utama Desain (*Design Principles*)

1. **Soft Frosted Glassmorphism:** Efek kaca beku modern dengan translusensi berlapis (*layered translucent glass*), refraksi cahaya tinggi (`backdrop-filter: blur(24px - 32px) saturate(190%)`), pantulan specular beveled halus (*subtle specular highlight* `inset 0 1.5px 1.5px 0 rgba(255,255,255,0.95)`), dan pendaran cahaya ambien lembut (*ambient light glow*).
2. **Academic Modern & Trustworthy:** Tampilan bersih, elegan, dan profesional yang mencerminkan integritas institusi pendidikan tinggi.
3. **Scannable & Action-Oriented:** Mengedepankan hierarki visual yang jelas untuk membaca status reservasi (slot 30 menit), pelaporan kerusakan, dan navigasi peran tanpa distraksi.
4. **Signature Color Contrast:** Kontras harmonis antara *Deep Forest Green* (`#0F5143`, `#08332B`), permukaan kaca transparan/putih bersih, dan aksen fokus input *Soft Light Green / Mint* (`#E8F8F3`, `#10B981`).
5. **Strict Role-Driven UI:** Pemisahan fungsionalitas yang tegas antara hak akses Administrator, Petugas, dan Pengguna Umum.

---

## 2. Palet Warna (*Color Palette*) & Token Visual

### 2.1. Brand / Primary Colors (Deep Forest Green)
Warna identitas utama yang digunakan pada tombol aksi utama (CTA), header hero, navbar aktif, kartu fitur showcase, dan border kontras.

| Peran | Token / Tailwind Class | Hex Code | Penggunaan |
| :--- | :--- | :--- | :--- |
| **Primary 900 (Deep Forest)** | `brand-900` | `#08332B` | Latar panel hero showcase, motif blueprint grid, backdrop gelap |
| **Primary 800 (Forest Base)** | `brand-800` | `#0F5143` | Tombol CTA utama (`.kezak-btn-primary`), logo brand, badge aktif |
| **Primary 700 (Hover State)** | `brand-700` | `#146353` | Status hover tombol CTA & link navigasi aktif |
| **Primary 500 (Teal Green)**  | `brand-500` | `#107B67` | Ikon pendukung, progress bar, border aktif |
| **Primary 100 (Mint Light)**  | `brand-100` | `#E6F4F1` | Background badge sukses, highlight baris tabel |
| **Primary 50 (Mint Surface)** | `brand-50`  | `#F2F9F7` | Background container menu aktif sidebar, pill filter |

---

### 2.2. Signature Form & Accent Colors (Soft Light Green / Mint)
Warna khas yang menjadi ciri visual utama pada kolom input formulir saat kondisi fokus (*onfocus / onclick / onactive*) dan indikator status penting.

| Peran | Token / Tailwind Class | Hex Code | Penggunaan |
| :--- | :--- | :--- | :--- |
| **Input Highlight Fill**  | `accent-input`  | `#E8F8F3` | Latar kolom input saat berfokus / aktif terisi (hijau muda segar) |
| **Input Highlight Border**| `accent-border` | `#10B981` | Border kolom input saat dalam kondisi fokus (`focus:border-[#10B981]`) |
| **Amber Warning (Base)**   | `warning-500`   | `#D97706` | Status "Menunggu Review", "Terpakai", indikator jadwal |
| **Amber Warning (Light)**  | `warning-100`   | `#FEF3C7` | Background badge status pending / peringatan |

---

### 2.3. Status & Functional Colors
Digunakan pada matriks ketersediaan slot waktu 30 menit, badge status laporan, dan indikator database.

| Status Fasilitas / Reservasi | Background | Text / Border | Keterangan |
| :--- | :--- | :--- | :--- |
| **Tersedia / Disetujui / Selesai** | `#DCFCE7` (`green-100`) | `#15803D` (`green-700`) | Slot jam kosong, reservasi disetujui, akun terverifikasi |
| **Terpakai / Diproses** | `#FEF3C7` (`amber-100`) | `#B45309` (`amber-700`) | Slot telah dibooking, laporan dalam penanganan |
| **Dalam Perbaikan / Ditolak** | `#FEE2E2` (`red-100`)   | `#B91C1C` (`red-700`)   | Fasilitas rusak/maintenance, laporan ditolak |
| **Menunggu Review** | `#E0F2FE` (`sky-100`)   | `#0369A1` (`sky-700`)   | Pengajuan reservasi belum divalidasi petugas |
| **Nonaktif / Slot Ditutup** | `#F1F5F9` (`slate-100`) | `#64748B` (`slate-500`) | Di luar jam operasional (07.00 - 20.00) |

---

### 2.4. Palet Warna Mode Gelap (*Dark Mode Palette - Obsidian Emerald*)
Digunakan saat pengguna mengaktifkan mode gelap pada antarmuka aplikasi.

| Peran | Token Visual | Nilai Hex / RGBA | Penggunaan |
| :--- | :--- | :--- | :--- |
| **Canvas Background** | `dark-canvas` | `#040908` | Latar belakang canvas keseluruhan halaman saat mode gelap |
| **Radial Glow Latar** | `dark-glow` | `#082620` | Pendaran gradasi radial tengah di belakang canvas |
| **Dark Glass Master** | `dark-glass-master` | `rgba(8, 20, 17, 0.65)` | Wadah master kartu kaca gelap (*master container card*) |
| **Dark Glass Panel** | `dark-glass-panel` | `rgba(12, 26, 22, 0.82)` | Panel formulir kaca beku gelap dengan saturasi 190% |
| **Dark Floating Card**| `dark-card` | `rgba(8, 26, 22, 0.88)` | Kartu ringkasan data, showcase widget, dan floating cards |
| **Dark Input Surface**| `dark-input-bg` | `rgba(14, 32, 26, 0.60)` | Kolom input transparan kaca gelap dengan teks putih |
| **Dark Input Focus** | `dark-input-focus` | `rgba(16, 44, 36, 0.85)` | Latar kolom input saat berfokus / aktif terisi |
| **Text Primary Dark** | `dark-text-100` | `#FFFFFF` | Seluruh judul, angka statistik, dan teks kunci (wajib putih cerah) |
| **Text Secondary Dark**| `dark-text-200` | `#F1F5F9` | Teks keterangan, baris data, dan legend status |
| **Text Muted Dark** | `dark-text-400` | `#CBD5E1` / `#94A3B8` | Label sekunder, waktu update, dan teks pembantu |
| **Teal Neon Accent** | `dark-teal-neon` | `#5EEAD4` | Jam operasional, progres selesai, link jadwal |
| **Amber Neon Accent**| `dark-amber-neon` | `#FCD34D` | Jam warning, progres penanganan, badge terpakai |

---

## 3. Sistem Frosted Glassmorphism (*Frosted Glass System*)

Sistem kaca beku diterapkan pada halaman otentikasi (`/login` & `/register`) dan elemen dashboard modern untuk menghadirkan kesan mewah, ringan, dan futuristik.

### 3.1. Anatomi Latar & Ambient Blobs
Efek *frosted glass* memerlukan elemen grafis di belakangnya agar refraksi kaca terlihat nyata:
* **Mode Terang (*Default Light Canvas*):** Menggunakan warna mint lembut `#EDF7F4` dengan gradasi radial lembut serta *ambient blur orbs* hijau zamrud/toska transparan (`rgba(52, 211, 153, 0.25)` dan `rgba(94, 234, 212, 0.25)`).
* **Mode Gelap (*Full-Page Dark Canvas*):** Seluruh kanvas (`html` dan `body`) bertransformasi menjadi obsidian malam pekat `#040908` dengan gradasi radial ke `#082620` dan `#020706`, serta *ambient blobs* yang otomatis meredup (opasitas 8% - 15%) untuk menghadirkan atmosfer nebula malam yang elegan.

```html
<!-- Base Canvas Body (Dual Mode) -->
<body class="min-h-screen relative overflow-x-hidden font-sans antialiased flex flex-col justify-center">
    <!-- Ambient Light Blobs (Penyedia Refraksi Kaca) -->
    <div class="ambient-blob-1 fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full blur-[120px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-2 fixed bottom-[-10%] right-[-5%] w-[600px] h-[600px] rounded-full blur-[140px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-3 fixed top-[40%] right-[30%] w-[350px] h-[350px] rounded-full blur-[100px] pointer-events-none -z-0"></div>
    
    <!-- Blobs terfokus di balik Panel Kaca Form Kiri -->
    <div class="ambient-blob-focus-1 fixed top-[18%] left-[6%] w-[480px] h-[480px] rounded-full blur-[105px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-focus-2 fixed bottom-[12%] left-[18%] w-[420px] h-[420px] rounded-full blur-[115px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-focus-3 fixed top-[15%] left-[28%] w-[320px] h-[320px] rounded-full blur-[85px] pointer-events-none -z-0"></div>
</body>
```

### 3.2. Master Glass Container
Wadah utama kartu otentikasi membungkus panel form dan panel showcase:
* **Mode Terang:** `background: rgba(255, 255, 255, 0.40); backdrop-filter: blur(28px); border: 1px solid rgba(255, 255, 255, 0.70); box-shadow: 0 25px 60px rgba(15, 81, 67, 0.08); rounded-[32px];`
* **Mode Gelap:** `background: rgba(8, 20, 17, 0.65); backdrop-filter: blur(28px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85); rounded-[32px];`

### 3.3. Spesifikasi Panel Kaca Formulir (`.auth-glass-panel`)
Panel formulir kiri menggunakan kaca dengan saturasi warna dan pantulan garis *specular* atas:
```css
/* Mode Terang */
.auth-glass-panel {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.65) 0%, rgba(255, 255, 255, 0.38) 100%);
    backdrop-filter: blur(32px) saturate(190%);
    -webkit-backdrop-filter: blur(32px) saturate(190%);
    border-color: rgba(255, 255, 255, 0.60);
    box-shadow: 
        inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95),
        inset -1px 0 1px 0 rgba(255, 255, 255, 0.40);
}

/* Mode Gelap */
html.dark .auth-glass-panel {
    background: linear-gradient(135deg, rgba(12, 26, 22, 0.82) 0%, rgba(6, 15, 13, 0.92) 100%) !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    box-shadow: 
        inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.12),
        inset -1px 0 1px 0 rgba(255, 255, 255, 0.05) !important;
}
```

### 3.4. Input Formulir Kaca Beku (`.kezak-input`)
Input transparan yang beradaptasi dengan warna latar, berubah menjadi hijau muda segar saat berfokus (*onfocus / onclick / onactive*):
```css
/* Mode Terang */
.kezak-input {
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.85);
    background: rgba(255, 255, 255, 0.50);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    box-shadow: 
        inset 0 1px 2px rgba(0, 0, 0, 0.03), 
        0 1px 2px rgba(255, 255, 255, 0.65);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    color: #0F172A;
}

.kezak-input:focus,
.kezak-input:focus-within,
.kezak-input:active {
    background-color: #E8F8F3 !important;
    border-color: #10B981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.22), inset 0 1px 1px rgba(0, 0, 0, 0.02) !important;
    outline: none;
}

/* Mode Gelap */
html.dark .kezak-input {
    background: rgba(14, 32, 26, 0.60) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #FFFFFF !important;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.35) !important;
}

html.dark .kezak-input:focus,
html.dark .kezak-input:focus-within,
html.dark .kezak-input:active {
    background-color: rgba(16, 44, 36, 0.85) !important;
    border-color: #10B981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35), inset 0 1px 1px rgba(0, 0, 0, 0.2) !important;
    color: #FFFFFF !important;
    outline: none;
}
```

### 3.5. Segmented Tab Switcher Kaca
Pengalih tab "Sign In / Sign Up" pada modul otentikasi:
* **Container:** 
  - Terang: `background: rgba(15, 23, 42, 0.05); border: 1px solid rgba(255, 255, 255, 0.70); box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);`
  - Gelap: `background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.10); box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);`
* **Tab Aktif:** 
  - Terang: `background: rgba(255, 255, 255, 0.95); color: #0F5143; font-weight: 700; border: 1px solid rgba(255, 255, 255, 0.95); shadow-sm;`
  - Gelap: `background: #0F5143; color: #FFFFFF; font-weight: 700; border: 1px solid rgba(16, 185, 129, 0.4); shadow-md;`
* **Tab Inaktif:** 
  - Terang: `color: #475569; hover:color: #0F172A;`
  - Gelap: `color: #94A3B8; hover:color: #F8FAFC;`

### 3.6. Glass Helper Classes untuk Dashboard
Untuk kartu kaca pada dashboard operasional:
* `.glass-shell`: `background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.5);`
* `.glass-card-main`: `background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);`
* `.glass-card-nested`: `background: rgba(255, 255, 255, 0.72); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.8);`

### 3.7. Sistem Mode Gelap (*Obsidian Frosted Glassmorphism & Theme Toggle*)

Mode Gelap pada FacilityHub dirancang dengan pendekatan **Full-Page Dark Canvas** yang menggabungkan kedalaman warna malam *obsidian emerald* dengan estetika kaca beku mewah (*frosted glassmorphism*):

#### 1. Prinsip Gelap Menyeluruh (*Full-Page Dark Canvas*)
* **Latar Belakang Menyeluruh:** Seluruh kanvas (`html` dan `body`) bertransformasi menjadi gelap pekat (`#040908` dengan gradasi radial ke `#082620` dan `#020706`), memastikan tidak ada kebocoran warna mint atau putih di luar kartu.
* **Ambient Blobs Redup:** Pendaran *orbs* latar belakang otomatis meredup dengan opasitas rendah (8% - 15%) untuk menciptakan nuansa nebula malam yang elegan di balik panel kaca beku.

#### 2. Standar Tipografi Kontras Tinggi Wajib Putih (*White Text Standard*)
* **Aturan Kritis:** Seluruh teks judul fasilitas, jumlah reservasi, judul kartu floating, dan nama unit (yang pada mode terang berwarna hijau tua `#193B3A` atau `text-gray-800`) **wajib diubah menjadi Putih Bersih (`#FFFFFF`)** pada mode gelap agar memiliki keterbacaan (*readability*) sempurna dan tidak tenggelam di atas latar kartu gelap.
* **Teks Sekunder & Keterangan:** Menggunakan `#F1F5F9` untuk label item/jadwal, dan `#CBD5E1` / `#94A3B8` untuk teks pembantu/muted.
* **Aksen Waktu & Progres:** Menggunakan toska neon (`#5EEAD4`) dan kuning amber menyala (`#FCD34D`) untuk visibilitas instan.

#### 3. Kolom Input Transparan Berpendar (*Glowing Obsidian Inputs*)
* **Default:** `background: rgba(14, 32, 26, 0.60)`, `border: 1px solid rgba(255, 255, 255, 0.12)`, warna teks `#FFFFFF`, placeholder `#64748B`.
* **Saat Berfokus / OnActive:** `background-color: rgba(16, 44, 36, 0.85) !important`, `border-color: #10B981 !important`, serta efek pendaran ring hijau muda `box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35)`.

#### 4. Komponen Toggle Interaktif & Pencegah Kedipan (FOUC)
* **Toggle Pill:** Diletakkan di pojok kanan atas panel autentikasi, memuat ikon Matahari (☀️) dan Bulan (🌙).
* **Indikator Status:** 
  - **Mode Terang:** Ikon matahari aktif (kuning amber di atas badge putih bershadow).
  - **Mode Gelap:** Ikon bulan aktif (putih di atas badge hijau zamrud `#059669`).
* **State Persistence:** Pilihan mode disimpan di `localStorage.setItem('facilityhub_theme', 'dark' | 'light')`.
* **Default Mode:** Secara bawaan (*default*) aplikasi menggunakan **Mode Terang** (tidak memaksa tema gelap dari sistem operasi perangkat).
* **Pencegah FOUC (Flash of Unstyled Content):** Skrip instan pada `<head>` layout memastikan tema gelap langsung terpasang sebelum DOM selesai dirender:
```javascript
(function() {
    try {
        if (localStorage.getItem('facilityhub_theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } catch (e) {}
})();
```

---

## 4. Sistem Ikon Tipe Fasilitas (*Facility Iconography*)

Untuk menjaga konsistensi visual dan menghindari representasi yang ambigu, setiap tipe fasilitas memiliki ikon semantik unik melalui komponen Blade terstandarisasi:
`<x-facility-icon :tipe="$tipe" class="w-6 h-6" />`

| Tipe Fasilitas | Simbol Semantik | Representasi Visual Ikon SVG | Penggunaan |
| :--- | :---: | :--- | :--- |
| **`aula`** | 🏛️ | Gedung pertemuan megah dengan atap pediment segitiga dan pilar/kolom klasik | Auditorium, Graha Widya, Gedung Serbaguna |
| **`ruang_kelas`** | 📋 | Papan tulis/whiteboard di atas easel presentasi perkuliahan dengan garis diagram | Ruang Teori, Ruang Seminar, Smart Classroom |
| **`laboratorium`** | 🧪 | Labu Erlenmeyer sains dengan takaran volume cairan dan uap reaksi | Lab Komputer, Lab Fisika, Lab Riset Terpadu |
| **`alat`** | 📽️ | Proyektor multimedia dengan sorot lensa optik dan kisi pendingin | LCD Proyektor, Perangkat Sound System, Mic Wireless |
| **`lapangan`** | 🏀 | Bola olahraga lapangan dengan kontur lengkung garis jahitan dinamis | Lapangan Futsal, Basket, Bulutangkis, Tenis Meja |
| **Default / Fallback** | 🏢 | Siluet gedung fasilitas umum bertingkat | Gedung penunjang operasional lainnya |

### Aturan Implementasi Ikon:
* **Ukuran Standar:** `w-4 h-4` (tabel/badge kecil), `w-5 h-5` (list navigasi), `w-6 h-6` hingga `w-7 h-7` (kartu event & widget hero).
* **Normalisasi:** Komponen secara otomatis menangani sinonim seperti `'ruang kelas'`, `'kelas'`, `'lab'`, dan `'peralatan'`.
* **Konteks Penggunaan:** Wajib digunakan pada kartu ringkasan *Upcoming Major Event*, kolom tipe tabel master fasilitas, dan katalog pencarian.

---

## 5. Arsitektur Ruang Kerja Administrator (*Admin Workspace*)

### 5.1. Batasan Tanggung Jawab (*Scope Separation*)
Sesuai rancangan sistem FacilityHub:
* **Admin** fokus secara eksklusif pada tata kelola master data: **Dashboard**, **Pengelolaan Fasilitas**, dan **Pengelolaan Akun Pengguna**.
* **Admin TIDAK mengelola reservasi harian maupun pelaporan kerusakan teknis** (tugas ini didelegasikan seutuhnya kepada akun peran **Petugas**).
* **Pengingat Jadwal Pemeliharaan:** Ditiadakan dari sidebar dan dashboard admin karena pemeliharaan rutin merupakan ranah operasional petugas lapangan.

### 5.2. Struktur Menu Sidebar Admin
Navigasi sidebar admin hanya terdiri dari 3 menu pokok:
1. **Dashboard:** Ringkasan statistik fasilitas, kesehatan sistem, dan verifikasi akun.
2. **Pengelolaan Fasilitas (`admin.facilities.index`):** Penambahan, perubahan status operasional, dan katalog fasilitas kampus.
3. **Pengelolaan Akun (`admin.users.index`):** Persetujuan aktivasi akun pendaftar baru (*pending verification*) dan manajemen peran.

### 5.3. Standarisasi Notifikasi Sidebar (Lingkaran Berwarna Minimalis)
Notifikasi pada sidebar admin dibuat minimalis berupa angka di dalam lingkaran warna murni (*pure circular badge*):
* **Merah (`bg-rose-500 text-white rounded-full font-bold`):** Menampilkan jumlah fasilitas yang berstatus **dalam perbaikan / rusak** pada menu *Pengelolaan Fasilitas*.
* **Kuning Terang (`bg-amber-100 text-amber-800 border border-amber-300 rounded-full font-bold shadow-xs`):** Menampilkan jumlah akun pengguna yang berstatus **menunggu verifikasi / pending** pada menu *Pengelolaan Akun*.

```html
<!-- Contoh Implementasi Badge Sidebar -->
<!-- 1. Fasilitas Rusak (Merah) -->
@if ($sidebarDamagedCount > 0)
    <span class="w-5 h-5 rounded-full bg-rose-500 text-white text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs">
        {{ $sidebarDamagedCount }}
    </span>
@endif

<!-- 2. Akun Pending (Kuning Terang) -->
@if ($sidebarPendingCount > 0)
    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs">
        {{ $sidebarPendingCount }}
    </span>
@endif
```

### 5.4. Tombol Aksi Cepat (*Quick Action Cards*)
Pada sidebar admin desktop, disediakan panel pintasan langsung ke formulir entri data tanpa harus membuka tabel daftar terlebih dahulu:
1. **Tambah Akun Langsung:** Mengarah langsung ke formulir `route('admin.users.create')` dengan ikon personil/user plus.
2. **Tambah Fasilitas Langsung:** Mengarah langsung ke formulir `route('admin.facilities.create')` dengan ikon gedung/fasilitas plus.

---

## 6. Widget Dashboard & Visualisasi Data

### 6.1. Kartu Event Mendatang (*Upcoming Major Event Showcase*)
* Menampilkan reservasi besar terdekat yang disetujui.
* **Ikon Dinamis:** Menggunakan wadah persegi rounded `bg-[#0F5143] text-white` berukuran `w-12 h-12` yang memuat `<x-facility-icon :tipe="$tipe" />`.
* **Kebersihan Visual:** Logo stempel melingkar redundan dihilangkan agar fokus pengguna tertuju langsung pada informasi esensial: nama event, gedung, tanggal, dan rentang jam WIB.

### 6.2. Batang Statistik Proporsional (*Weekly Reservation Statistic Bars*)
Lebar visual batang progress (`div` indikator) **wajib terikat proporsional** terhadap angka riil statistik yang dihitung:
```blade
@php
    $resDenominator = max($resTotal, ($resApproved + $resPending + $resRejected));
    $pctApproved = $resDenominator > 0 ? min(100, (int) round(($resApproved / $resDenominator) * 100)) : 0;
    $pctPending = $resDenominator > 0 ? min(100, (int) round(($resPending / $resDenominator) * 100)) : 0;
    $pctRejected = $resDenominator > 0 ? min(100, (int) round(($resRejected / $resDenominator) * 100)) : 0;
@endphp

<!-- Bar Disetujui -->
<div class="flex-1 h-3 rounded-full bg-slate-200/70 overflow-hidden p-0.5">
    <div class="h-full rounded-full bg-[#0F5143] transition-all duration-500" style="width: {{ $pctApproved }}%"></div>
</div>
```

---

## 7. Tipografi (*Typography*)

* **Font Utama Aplikasi:** `Plus Jakarta Sans`, `Inter`, atau sans-serif sistem modern.
* **Font Monospace (Kode Booking, ID Tiket, Serial Inventaris):** `JetBrains Mono` atau `Fira Code`.

| Elemen | Ukuran (px/rem) | Weight | Line Height | Penggunaan |
| :--- | :--- | :--- | :--- | :--- |
| **Display / H1**  | `30px - 32px` / `2.0rem` | Bold (`700 - 800`) | `1.25` | Judul Utama Welcome Auth, Judul Halaman Dashboard |
| **Heading 2 (H2)**| `22px - 24px` / `1.5rem` | Bold (`700`)       | `1.3`  | Judul Section Halaman, Header Tabel |
| **Heading 3 (H3)**| `16px - 18px` / `1.125rem`| SemiBold (`600`)   | `1.4`  | Header Kartu Widget, Subjudul Modal |
| **Subheading (H4)**| `14px - 16px` / `1.0rem` | SemiBold (`600`)   | `1.4`  | Label field formulir input, judul popover |
| **Body Regular**  | `13px - 14px` / `0.875rem`| Regular (`400`)    | `1.5`  | Deskripsi fasilitas, teks konten tabel |
| **Small / Badge** | `11px - 12px` / `0.75rem` | Medium (`500 - 600`)| `1.4`  | Badge status, pill tipe, helper text |
| **Micro Text**    | `10px - 11px` / `0.6875rem`| Bold (`700`)       | `1.2`  | Angka counter notifikasi, label copyright |

---

## 8. Corner Radius, Spacing, dan Elevasi

### 8.1. Corner Radius
* **Master Glass Container / Showcase Outer:** `32px` (`rounded-[32px]`).
* **Modal Dialog & Kartu Utama Dashboard:** `24px` (`rounded-3xl`).
* **Widget Kartu / Event Card / Form Panel:** `16px` (`rounded-2xl`).
* **Input Formulir & Tombol CTA:** `12px` (`rounded-xl`).
* **Badges, Counter Circles, & Pills:** `9999px` (`rounded-full`).

### 8.2. Elevasi & Bayangan (*Box Shadows*)
* **Glass Container Float:** `box-shadow: 0 25px 60px rgba(0, 0, 0, 0.18)`
* **Frosted Panel Top Specular Bevel:** `box-shadow: inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95), inset -1px 0 1px 0 rgba(255, 255, 255, 0.40)`
* **Primary Button Active Glow:** `box-shadow: 0 4px 14px rgba(15, 81, 67, 0.28)`
* **Standard Soft Card Elevation:** `box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06)`

---

## 9. Visualisasi Slot Waktu 30 Menit (Matriks Reservasi)

Setiap fasilitas diatur berdasarkan jam operasional kampus (07.00 - 20.00 WIB) dalam partisi slot 30 menit:
* **Slot Box Size:** Tinggi minimal `36px` dengan padding responsif.
* **Slot Tersedia (Kosong):** Background `#FFFFFF`, Border `#E2E8F0`, Teks `#15803D`, Hover `#DCFCE7`.
* **Slot Terpilih (Sedang Diajukan):** Background `#0F5143`, Teks `#FFFFFF`, Border `#0F5143`.
* **Slot Terpakai (Reservasi Disetujui):** Background `#FEF3C7`, Border `#FDE68A`, Teks `#B45309`, Kursor dinonaktifkan.
* **Slot Dalam Pemeliharaan / Rusak:** Background `#FEE2E2`, Border `#FCA5A5`, Teks `#B91C1C`, Kursor dinonaktifkan (`cursor-not-allowed`).

---

## 10. Konfigurasi Tailwind CSS (`tailwind.config.js`)

Untuk memastikan seluruh token warna dan styling frosted glass terkompilasi optimal oleh Tailwind CSS Vite build:

```javascript
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#F2F9F7',
          100: '#E6F4F1',
          500: '#107B67',
          700: '#146353',
          800: '#0F5143', // Deep Forest Green Base
          900: '#08332B', // Dark Blueprint Grid & Hero Background
        },
        inputGreen: {
          light: '#E8F8F3', // Signature Frosted Light Green Input Focus
          border: '#10B981',
        }
      },
      borderRadius: {
        '2xl': '16px',
        '3xl': '24px',
        '4xl': '32px',
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      boxShadow: {
        'glass': '0 25px 60px rgba(0, 0, 0, 0.18)',
        'glass-card': '0 8px 30px rgba(0, 0, 0, 0.06)',
        'cta': '0 4px 14px rgba(15, 81, 67, 0.28)',
      }
    },
  },
  plugins: [],
}
```

---

## 11. Panduan Prompting untuk AI Coding Assistant

Ketika meminta AI membuat komponen, halaman baru, atau memperbaiki tampilan, sertakan format instruksi berikut:

> *"Gunakan standar dari `DESIGN.md`. Terapkan tema **Soft Frosted Glassmorphism** dengan warna utama `brand-800` (#0F5143), input formulir bertranslusensi yang berubah menjadi hijau muda `#E8F8F3` (border `#10B981`) saat fokus/aktif, wadah kartu ber-radius halus dengan specular highlight, serta ikon tipe fasilitas semantik via `<x-facility-icon :tipe="$tipe" />`. Untuk **Mode Gelap (*Dark Mode*)**, terapkan kanvas gelap menyeluruh (#040908), ambient blobs redup, dan pastikan seluruh teks judul fasilitas, angka reservasi, dan judul kartu wajib berwarna **Putih Bersih (#FFFFFF)** agar kontras dan terbaca jelas. Pastikan sidebar admin hanya mengelola fasilitas dan akun dengan notifikasi lingkaran angka murni (merah untuk fasilitas rusak, kuning terang untuk akun pending)."*