@extends('layouts.app')

@section('title', 'Katalog Fasilitas Kampus')
@section('page-title', 'Katalog & Ketersediaan Fasilitas')

@section('content')
<div class="welcome-section">
    <div>
        <h1>Katalog & Ketersediaan Fasilitas Kampus</h1>
        <p>Lihat ketersediaan ruangan dan sarana prasarana per slot 30 menit (Jam Operasional: 07.00–20.00 WIB).</p>
    </div>
</div>

{{-- SEARCH & FILTER CARD (US 2) --}}
<div class="search-card">
    <form method="GET" action="{{ route('facilities') }}">
        <div class="search-bar">
            <span class="search-symbol">⌕</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama ruang, laboratorium, aula, alat, lapangan, atau lokasi..."
            >
            <button type="submit">
                Cari
            </button>
        </div>

        <div class="filter-group" style="flex-wrap: wrap; align-items: center;">
            {{-- Filter Tanggal (US 1) --}}
            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal:</label>
                <input
                    type="date"
                    name="tanggal"
                    value="{{ $selectedDate }}"
                    style="border: 1px solid #dbe1ea; background: white; padding: 8px 12px; border-radius: 8px; color: #475569; font-size: 12px; outline: none;"
                    onchange="this.form.submit()"
                >
            </div>

            {{-- Filter Tipe --}}
            <select name="tipe" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="ruang_kelas" {{ request('tipe') === 'ruang_kelas' ? 'selected' : '' }}>Ruang Kelas</option>
                <option value="laboratorium" {{ request('tipe') === 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                <option value="aula" {{ request('tipe') === 'aula' ? 'selected' : '' }}>Aula</option>
                <option value="lapangan" {{ request('tipe') === 'lapangan' ? 'selected' : '' }}>Lapangan</option>
                <option value="alat" {{ request('tipe') === 'alat' ? 'selected' : '' }}>Alat / Perangkat</option>
            </select>

            {{-- Filter Lokasi --}}
            <select name="lokasi" onchange="this.form.submit()">
                <option value="">Semua Lokasi</option>
                @foreach ($availableLocations as $loc)
                    <option value="{{ $loc }}" {{ request('lokasi') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                @endforeach
            </select>

            {{-- Filter Kapasitas --}}
            <select name="kapasitas" onchange="this.form.submit()">
                <option value="">Semua Kapasitas</option>
                <option value="< 30 orang" {{ request('kapasitas') === '< 30 orang' ? 'selected' : '' }}>&lt; 30 orang</option>
                <option value="30 - 50 orang" {{ request('kapasitas') === '30 - 50 orang' ? 'selected' : '' }}>30 - 50 orang</option>
                <option value="> 50 orang" {{ request('kapasitas') === '> 50 orang' ? 'selected' : '' }}>&gt; 50 orang</option>
            </select>

            @if (request()->hasAny(['search', 'tipe', 'lokasi', 'kapasitas', 'tanggal']))
                <a href="{{ route('facilities') }}" style="font-size: 12px; color: #ef4444; text-decoration: none; padding: 8px 12px; font-weight: 600;">
                    Reset Filter
                </a>
            @endif
        </div>
    </form>
</div>

{{-- SUMMARY METRICS --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-icon blue">▦</div>
        <div>
            <span>Total Sarana</span>
            <strong>{{ $stats['total'] }}</strong>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-icon green">✓</div>
        <div>
            <span>Siap Digunakan (Aktif)</span>
            <strong>{{ $stats['aktif'] }}</strong>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-icon orange">◷</div>
        <div>
            <span>Tanggal Ditinjau</span>
            <strong>{{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}</strong>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-icon red">!</div>
        <div>
            <span>Dalam Perbaikan</span>
            <strong>{{ $stats['dalam_perbaikan'] }}</strong>
        </div>
    </div>
</div>

{{-- SECTION HEADER --}}
<div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2>Daftar Fasilitas & Jadwal Ketersediaan</h2>
        <p>Setiap fasilitas memiliki 26 slot waktu (07.00 - 20.00). Klik tombol untuk memeriksa detail tiap slot.</p>
    </div>
    <span style="font-size: 12px; color: #64748b; background: white; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
        Menampilkan {{ $facilities->count() }} dari {{ $facilities->total() }} fasilitas
    </span>
</div>

{{-- FACILITY CARDS GRID (US 1 & US 2) --}}
<div class="facility-grid">
    @forelse ($facilities as $facility)
        <div class="facility-card">
            <div class="facility-cover">
                @if ($facility->status === 'dalam_perbaikan')
                    <span class="status-badge maintenance">
                        Dalam Perbaikan
                    </span>
                @elseif ($facility->status === 'nonaktif')
                    <span class="status-badge" style="background: #e2e8f0; color: #475569;">
                        Nonaktif
                    </span>
                @elseif ($facility->is_fully_booked)
                    <span class="status-badge busy">
                        Penuh (0/26 Slot)
                    </span>
                @else
                    <span class="status-badge available">
                        Tersedia ({{ $facility->available_slots_count }}/26 Slot)
                    </span>
                @endif

                <span class="room-code">
                    {{ strtoupper(substr($facility->nama, 0, 7)) }}
                </span>
            </div>

            <div class="facility-body">
                <span class="facility-category">
                    {{ ucwords(str_replace('_', ' ', $facility->tipe)) }}
                </span>

                <h3>{{ $facility->nama }}</h3>

                <div class="facility-meta">
                    <span>⌖ {{ $facility->lokasi }}</span>
                    <span>♟ {{ $facility->kapasitas ? $facility->kapasitas . ' orang' : 'Fleksibel' }}</span>
                </div>

                @if ($facility->deskripsi)
                    <p style="font-size: 12px; color: #64748b; margin: 8px 0 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $facility->deskripsi }}
                    </p>
                @endif

                <div class="next-slot">
                    @if ($facility->status === 'dalam_perbaikan')
                        <span>Status Pemeliharaan</span>
                        <strong class="danger-text">Sedang perbaikan teknisi</strong>
                    @elseif ($facility->status === 'nonaktif')
                        <span>Status Fasilitas</span>
                        <strong style="color: #64748b;">Tidak aktif sementara</strong>
                    @elseif ($facility->next_available_slot)
                        <span>Slot tersedia berikutnya ({{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d M') }})</span>
                        <strong style="color: #16a34a;">{{ $facility->next_available_slot }}</strong>
                    @else
                        <span>Status Slot</span>
                        <strong class="danger-text">Semua slot sudah dipesan</strong>
                    @endif
                </div>

                <button
                    type="button"
                    class="detail-button"
                    onclick="openScheduleModal({{ $facility->id }}, '{{ addslashes($facility->nama) }}', '{{ ucwords(str_replace('_', ' ', $facility->tipe)) }}', '{{ addslashes($facility->lokasi) }}', '{{ $facility->status }}')"
                >
                    Cek 26 Slot Waktu
                </button>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; background: white; padding: 48px; border-radius: 14px; text-align: center; border: 1px dashed #cbd5e1;">
            <div style="font-size: 36px; margin-bottom: 12px;">🔍</div>
            <h3 style="font-size: 18px; margin: 0 0 6px;">Tidak ada fasilitas yang sesuai</h3>
            <p style="color: #64748b; font-size: 13px; margin: 0 0 16px;">Coba ubah kata kunci pencarian atau sesuaikan filter tipe, lokasi, dan kapasitas.</p>
            <a href="{{ route('facilities') }}" style="display: inline-block; padding: 10px 18px; background: #2563eb; color: white; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600;">
                Tampilkan Semua Fasilitas
            </a>
        </div>
    @endforelse
</div>

{{-- PAGINATION --}}
<div style="margin-top: 28px;">
    {{ $facilities->links() }}
</div>

{{-- MODAL DETAIL 26 SLOT WAKTU (US 1) --}}
<div id="scheduleModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 1000; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 16px; width: 100%; max-width: 720px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #e2e8f0; display: flex; flex-direction: column;">
        {{-- Modal Header --}}
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-start; position: sticky; top: 0; background: white; z-index: 10;">
            <div>
                <span id="modalFacilityType" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #2563eb; letter-spacing: 0.5px;"></span>
                <h3 id="modalFacilityName" style="font-size: 20px; margin: 4px 0 2px; color: #0f172a;"></h3>
                <p id="modalFacilityLocation" style="font-size: 12px; color: #64748b; margin: 0;"></p>
            </div>
            <button type="button" onclick="closeScheduleModal()" style="border: none; background: #f1f5f9; border-radius: 50%; width: 34px; height: 34px; font-size: 16px; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center;">
                ✕
            </button>
        </div>

        {{-- Modal Body --}}
        <div style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
                <div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700;">Jadwal Slot 30 Menit</h4>
                    <span style="font-size: 12px; color: #64748b;">Tanggal: <strong id="modalDateLabel">{{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</strong></span>
                </div>
                <div style="display: flex; gap: 12px; font-size: 12px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #16a34a;"></span> Tersedia
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #dc2626;"></span> Tidak Tersedia
                    </span>
                </div>
            </div>

            {{-- Slot Grid Container --}}
            <div id="slotsLoading" style="text-align: center; padding: 40px; color: #64748b; font-size: 13px;">
                Memuat data ketersediaan slot...
            </div>

            <div id="slotsContainer" style="display: none; grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 10px;">
                {{-- Diisi secara dinamis oleh JavaScript --}}
            </div>

            {{-- Privacy Notice (US 1) --}}
            <div style="margin-top: 20px; padding: 12px 14px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 11px; color: #64748b; display: flex; align-items: center; gap: 8px;">
                <span>🔒</span>
                <span>Sesuai kebijakan kampus, status slot hanya menampilkan status ketersediaan tanpa memuat informasi identitas pemesan maupun tujuan penggunaan.</span>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; border-radius: 0 0 16px 16px;">
            <button type="button" onclick="closeScheduleModal()" style="border: 1px solid #cbd5e1; background: white; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; cursor: pointer;">
                Tutup
            </button>

            @auth
                <a href="{{ route('reservation') }}" style="display: inline-block; background: #2563eb; color: white; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                    + Ajukan Reservasi
                </a>
            @else
                <a href="{{ route('login') }}" style="display: inline-block; background: #2563eb; color: white; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                    Masuk Akun untuk Reservasi
                </a>
            @endauth
        </div>
    </div>
</div>

<script>
    const selectedDate = '{{ $selectedDate }}';

    function openScheduleModal(facilityId, name, type, location, status) {
        const modal = document.getElementById('scheduleModal');
        const modalName = document.getElementById('modalFacilityName');
        const modalType = document.getElementById('modalFacilityType');
        const modalLocation = document.getElementById('modalFacilityLocation');
        const loading = document.getElementById('slotsLoading');
        const container = document.getElementById('slotsContainer');

        modalName.innerText = name;
        modalType.innerText = type;
        modalLocation.innerText = '⌖ ' + location;

        loading.style.display = 'block';
        container.style.display = 'none';
        container.innerHTML = '';
        modal.style.display = 'flex';

        fetch(`/facilities/${facilityId}/schedule?date=${selectedDate}`)
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';
                container.style.display = 'grid';

                data.slots.forEach(slot => {
                    const card = document.createElement('div');
                    card.style.padding = '8px 10px';
                    card.style.borderRadius = '8px';
                    card.style.fontSize = '12px';
                    card.style.display = 'flex';
                    card.style.flexDirection = 'column';
                    card.style.alignItems = 'center';
                    card.style.justifyContent = 'center';
                    card.style.textAlign = 'center';

                    if (slot.is_available) {
                        card.style.background = '#f0fdf4';
                        card.style.border = '1px solid #bbf7d0';
                        card.style.color = '#15803d';
                        card.innerHTML = `<span style="font-weight: 700; font-size: 11px;">${slot.label}</span><span style="font-size: 10px; margin-top: 2px;">Tersedia</span>`;
                    } else {
                        card.style.background = '#fef2f2';
                        card.style.border = '1px solid #fecaca';
                        card.style.color = '#dc2626';
                        card.innerHTML = `<span style="font-weight: 700; font-size: 11px; text-decoration: line-through;">${slot.label}</span><span style="font-size: 10px; margin-top: 2px; font-weight: 600;">${slot.status_label}</span>`;
                    }

                    container.appendChild(card);
                });
            })
            .catch(err => {
                loading.innerText = 'Gagal memuat jadwal. Silakan coba lagi.';
            });
    }

    function closeScheduleModal() {
        document.getElementById('scheduleModal').style.display = 'none';
    }

    // Tutup modal jika klik di luar area dialog
    window.onclick = function(event) {
        const modal = document.getElementById('scheduleModal');
        if (event.target === modal) {
            closeScheduleModal();
        }
    }
</script>
@endsection