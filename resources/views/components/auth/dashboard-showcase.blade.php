<div class="relative w-full h-full min-h-[680px] lg:min-h-full rounded-3xl kezak-grid-bg text-white flex flex-col justify-between p-6 sm:p-10 overflow-hidden shadow-2xl">
    
    <!-- Top Area: 3 Stacked Floating Facility Cards -->
    <div class="relative w-full h-[360px] sm:h-[400px] mb-4 select-none">
        
        <!-- CARD 1: Status Reservasi (Top Left/Center) -->
        <div class="showcase-card showcase-card-plan p-4 text-gray-800">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-[#193B3A] showcase-text-primary">Jadwal & Reservasi</span>
                <span class="text-[10px] font-medium text-gray-500 bg-gray-100 showcase-card-pill rounded-full px-2 py-0.5 flex items-center gap-1 cursor-pointer">
                    Minggu Ini
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>

            <!-- Donut Chart & Balance -->
            <div class="flex items-center gap-3 mb-3">
                <!-- SVG Donut Chart -->
                <div class="relative w-16 h-16 shrink-0">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <!-- Background track -->
                        <path class="text-gray-100 showcase-chart-track" stroke-width="4.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Segment 1: Disetujui (Teal) 60% -->
                        <path class="text-[#0D9488]" stroke-dasharray="60, 100" stroke-width="4.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Segment 2: Menunggu (Yellow) 25% -->
                        <path class="text-[#FBBF24]" stroke-dasharray="25, 100" stroke-dashoffset="-62" stroke-width="4.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Segment 3: Selesai (Orange) 15% -->
                        <path class="text-[#F97316]" stroke-dasharray="12, 100" stroke-dashoffset="-88" stroke-width="4.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                </div>

                <div class="leading-tight">
                    <div class="text-[15px] font-extrabold text-[#193B3A] showcase-text-primary tracking-tight">24 Reservasi</div>
                    <div class="text-[10px] text-gray-500 showcase-text-muted mb-1">Status Pengajuan</div>
                    <div class="space-y-0.5 text-[9px] text-gray-600 showcase-text-secondary">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#0D9488] shrink-0"></span>
                            <span>Disetujui Petugas</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FBBF24] shrink-0"></span>
                            <span>Menunggu Review</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#F97316] shrink-0"></span>
                            <span>Selesai Digunakan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Penggunaan Hari Ini -->
            <div class="pt-2 border-t border-gray-100 showcase-card-border">
                <div class="text-[10px] font-bold text-gray-700 showcase-text-primary mb-1.5">Jadwal Mendatang</div>
                <div class="space-y-1.5 text-[9px]">
                    <div>
                        <div class="flex justify-between text-gray-600 showcase-text-secondary font-medium">
                            <span>Lab Komputer Terpadu</span>
                            <span class="text-teal-700 showcase-highlight-teal font-semibold">08:00 - 10:30</span>
                        </div>
                        <div class="w-full bg-gray-100 showcase-progress-track rounded-full h-1 mt-0.5 overflow-hidden">
                            <div class="bg-[#0D9488] h-full rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-gray-600 showcase-text-secondary font-medium">
                            <span>Auditorium Utama</span>
                            <span class="text-amber-700 showcase-highlight-amber font-semibold">13:00 - 16:00</span>
                        </div>
                        <div class="w-full bg-gray-100 showcase-progress-track rounded-full h-1 mt-0.5 overflow-hidden">
                            <div class="bg-[#F59E0B] h-full rounded-full" style="width: 50%"></div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('facilities') }}" class="block w-full mt-2.5 py-1 text-[9px] font-semibold text-[#0D9488] bg-[#DCEFEF]/60 hover:bg-[#DCEFEF] showcase-btn-secondary rounded-lg transition-colors text-center">
                    Lihat Jadwal Fasilitas
                </a>
            </div>
        </div>

        <!-- CARD 2: Pelaporan Fasilitas (Top Right / Background) -->
        <div class="showcase-card showcase-card-funds p-4 text-gray-800">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-bold text-[#193B3A] showcase-text-primary">Pelaporan Kerusakan</span>
                <span class="text-[9px] font-semibold text-teal-800 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200/60 showcase-badge-teal">Aktif Diproses</span>
            </div>

            <!-- Item 1: AC Ruang Seminar A -->
            <div class="mb-3">
                <div class="flex items-center justify-between text-[11px] font-semibold text-[#193B3A] showcase-text-primary">
                    <span>AC Ruang Seminar A</span>
                    <span class="text-[9px] text-gray-400 showcase-text-muted font-normal">Teknisi Bertugas</span>
                </div>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-[13px] font-bold text-[#193B3A] showcase-text-primary">Perbaikan Unit</span>
                    <span class="text-[9px] text-teal-600 showcase-highlight-teal font-medium">· Tahap Final</span>
                </div>
                <div class="w-full bg-gray-100 showcase-progress-track rounded-full h-1.5 mt-1 overflow-hidden">
                    <div class="bg-[#0D9488] h-full rounded-full" style="width: 80%"></div>
                </div>
                <div class="flex justify-between text-[8px] text-gray-400 showcase-text-muted mt-0.5">
                    <span>Estimasi Selesai</span>
                    <span class="text-[#0D9488] showcase-highlight-teal font-semibold">Hari ini (80%)</span>
                </div>
            </div>

            <!-- Item 2: Proyektor Multimedia -->
            <div class="pt-2 border-t border-gray-100 showcase-card-border">
                <div class="flex items-center justify-between text-[11px] font-semibold text-[#193B3A] showcase-text-primary">
                    <span>Proyektor Lab C-102</span>
                    <span class="text-[9px] text-gray-400 showcase-text-muted font-normal">Verifikasi Laporan</span>
                </div>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-[13px] font-bold text-[#193B3A] showcase-text-primary">Penggantian Lampu</span>
                    <span class="text-[9px] text-amber-600 showcase-highlight-amber font-medium">· Pengadaan Part</span>
                </div>
                <div class="w-full bg-gray-100 showcase-progress-track rounded-full h-1.5 mt-1 overflow-hidden">
                    <div class="bg-[#F59E0B] h-full rounded-full" style="width: 45%"></div>
                </div>
                <div class="flex justify-between text-[8px] text-gray-400 showcase-text-muted mt-0.5">
                    <span>Progres Penanganan</span>
                    <span class="text-[#F59E0B] showcase-highlight-amber font-semibold">Diproses (45%)</span>
                </div>
            </div>
        </div>

        <!-- CARD 3: Ketersediaan Fasilitas Kampus (Bottom Left / Forefront) -->
        <div class="showcase-card showcase-card-capital p-3.5 text-gray-800">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-bold text-[#193B3A] showcase-text-primary">Ketersediaan Kampus</span>
                <span class="text-[8px] font-medium text-gray-400 showcase-text-muted flex items-center gap-0.5">
                    <svg class="w-2.5 h-2.5 animate-spin" style="animation-duration: 6s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Real-time update
                </span>
            </div>

            <div class="flex items-baseline justify-between mb-2">
                <div>
                    <div class="text-[8px] text-gray-400 showcase-text-muted">Total Sarana & Prasarana</div>
                    <div class="text-sm font-extrabold text-[#193B3A] showcase-text-primary tracking-tight">48 Fasilitas</div>
                </div>
                <span class="text-[9px] font-bold text-emerald-700 bg-emerald-100 showcase-badge-green px-1.5 py-0.5 rounded-full flex items-center gap-0.5">
                    ↑ 92% Siap Pakai
                </span>
            </div>

            <!-- Asset Rows: Facilities -->
            <div class="space-y-1.5 text-[9px] border-t border-gray-100 showcase-card-border pt-1.5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-gray-800 showcase-text-primary">Gedung Serbaguna</div>
                        <div class="text-[8px] text-gray-400 showcase-text-muted">Kapasitas 500 orang · Audio ready</div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-1.5 py-0.5 text-[8px] font-bold text-emerald-700 bg-emerald-50 showcase-badge-green rounded">Tersedia</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-gray-800 showcase-text-primary">Smart Classroom C-201</div>
                        <div class="text-[8px] text-gray-400 showcase-text-muted">Gedung C Lantai 2 · 45 Kursi</div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-1.5 py-0.5 text-[8px] font-bold text-amber-700 bg-amber-50 showcase-badge-amber rounded">Terpakai</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-gray-800 showcase-text-primary">Lapangan Futsal & Basket</div>
                        <div class="text-[8px] text-gray-400 showcase-text-muted">Area Olahraga Outdoor</div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-1.5 py-0.5 text-[8px] font-bold text-emerald-700 bg-emerald-50 showcase-badge-green rounded">Tersedia</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('facilities') }}" class="block w-full mt-2.5 py-1.5 text-[9px] font-semibold text-white bg-[#003F3D] hover:bg-[#002B2A] showcase-btn-primary rounded-lg transition-colors text-center shadow-xs">
                Cek Ketersediaan Ruangan
            </a>
        </div>
    </div>

    <!-- Center/Bottom Content: Headline & Description -->
    <div class="relative z-20 flex flex-col items-center text-center mt-auto pb-2 sm:pb-4 px-2 max-w-lg mx-auto">
        <!-- Headline -->
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight mb-3">
            Pusat Terpadu Reservasi & Pelaporan Fasilitas Kampus
        </h2>

        <!-- Description -->
        <p class="text-xs sm:text-sm text-teal-100/75 leading-relaxed font-normal max-w-md">
            FacilityHub mempermudah civitas akademika dalam peminjaman ruangan, pengecekan jadwal ketersediaan, serta pemantauan status perbaikan sarana kampus secara real-time.
        </p>
    </div>
</div>
