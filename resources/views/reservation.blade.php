@extends('layouts.app')

@section('title', 'Reservasi Saya — Portal Fasilitas')
@section('page-title', 'Reservasi & Peminjaman Fasilitas')

@section('content')
    <div class="welcome-section" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; width: 100%;">
            <div>
                <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #1e293b;">Peminjaman Fasilitas Saya</h1>
                <p style="margin: 6px 0 0 0; color: #64748b; font-size: 14px;">
                    Pantau status verifikasi pengajuan ruangan dan ajukan peminjaman sarana prasarana kampus.
                </p>
            </div>

            <button type="button" id="toggleReservationForm" onclick="toggleForm()" style="border: none; border-radius: 10px; padding: 12px 20px; background: #2563eb; color: white; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                <span>+</span>
                <span id="toggleButtonText">Ajukan Peminjaman Baru</span>
            </button>
        </div>
    </div>

    {{-- ALERT PESAN STATUS --}}
    @if (session('status'))
        <div style="margin-bottom: 20px; padding: 14px 18px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; color: #166534; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 18px;">✓</span>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if (session('status_error'))
        <div style="margin-bottom: 20px; padding: 14px 18px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; color: #991b1b; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 18px;">⚠️</span>
            <span>{{ session('status_error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div style="margin-bottom: 20px; padding: 14px 18px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; color: #991b1b; font-size: 13px;">
            <strong style="display: block; margin-bottom: 6px; font-size: 14px;">Terdapat kesalahan pada pengajuan:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM PENGAJUAN RESERVASI (TOGGLEABLE) --}}
    <div id="reservationFormWrapper" style="{{ ($selectedFacilityId || $errors->any()) ? 'display: block;' : 'display: none;' }} margin-bottom: 32px; max-width: 820px;">
        <div class="search-card" style="border: 2px solid #93c5fd; background: #ffffff; padding: 24px; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px;">
                <div>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #1e293b;">Formulir Peminjaman Fasilitas</h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 13px;">Pilih fasilitas, tanggal, dan durasi slot waktu (07.00 - 20.00 WIB).</p>
                </div>
                <button type="button" onclick="toggleForm()" style="background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; padding: 4px 8px;">✕</button>
            </div>

            <form method="POST" action="{{ route('reservations.store') }}">
                @csrf

                {{-- Pilih Fasilitas --}}
                <div style="margin-bottom: 18px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: #334155;">
                        Pilih Fasilitas / Ruangan Kampus <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="facility_id" required style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: white; font-size: 14px; color: #1e293b;">
                        <option value="">-- Pilih Fasilitas Kampus --</option>
                        @foreach ($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ (old('facility_id', $selectedFacilityId) == $facility->id) ? 'selected' : '' }}>
                                {{ $facility->nama }} ({{ ucfirst(str_replace('_', ' ', $facility->tipe)) }} • {{ $facility->lokasi }} • Kapasitas: {{ $facility->kapasitas ? $facility->kapasitas.' orang' : 'Fleksibel' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal & Waktu --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 18px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: #334155;">
                            Tanggal Pemakaian <span style="color: #ef4444;">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal"
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('tanggal', date('Y-m-d')) }}"
                            required
                            style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: white; font-size: 14px; color: #1e293b; box-sizing: border-box;"
                        >
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: #334155;">
                            Jam Mulai <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="start_time" required style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: white; font-size: 14px; color: #1e293b;">
                            <option value="">-- Pilih Jam Mulai --</option>
                            @php
                                $startTimes = ['07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30'];
                            @endphp
                            @foreach ($startTimes as $time)
                                <option value="{{ $time }}" {{ old('start_time') === $time ? 'selected' : '' }}>{{ $time }} WIB</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: #334155;">
                            Jam Selesai <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="end_time" required style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: white; font-size: 14px; color: #1e293b;">
                            <option value="">-- Pilih Jam Selesai --</option>
                            @php
                                $endTimes = ['07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00'];
                            @endphp
                            @foreach ($endTimes as $time)
                                <option value="{{ $time }}" {{ old('end_time') === $time ? 'selected' : '' }}>{{ $time }} WIB</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tujuan Penggunaan --}}
                <div style="margin-bottom: 22px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: #334155;">
                        Tujuan Penggunaan & Keterangan Kegiatan <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea
                        name="tujuan_penggunaan"
                        rows="3"
                        required
                        placeholder="Contoh: Perkuliahan pengganti mata kuliah Pemrograman Web, Rapat Kerja BEM, dll."
                        style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: white; font-size: 14px; color: #1e293b; resize: vertical; box-sizing: border-box;"
                    >{{ old('tujuan_penggunaan') }}</textarea>
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="submit" style="border: none; border-radius: 10px; padding: 12px 24px; background: #16a34a; color: white; font-weight: 700; font-size: 14px; cursor: pointer;">
                        Kirim Pengajuan Reservasi
                    </button>

                    <button type="button" onclick="toggleForm()" style="border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 20px; background: white; color: #475569; font-weight: 600; font-size: 14px; cursor: pointer;">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- DAFTAR RIWAYAT RESERVASI SAYA DARI DATABASE --}}
    <div style="max-width: 900px;">
        <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 800; color: #1e293b;">
            Daftar Pengajuan Reservasi Anda
        </h3>

        <div style="display: grid; gap: 16px;">
            @forelse ($reservations as $res)
                <div class="search-card" style="padding: 20px; border-radius: 14px; border: 1px solid #e2e8f0; background: white; display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                            <strong style="font-size: 17px; color: #0f172a;">{{ $res->facility->nama ?? 'Fasilitas #' . $res->facility_id }}</strong>
                            <span style="font-size: 11px; padding: 2px 8px; border-radius: 6px; background: #e0f2fe; color: #0369a1; font-weight: 600;">
                                {{ ucfirst(str_replace('_', ' ', $res->facility->tipe ?? 'Fasilitas')) }}
                            </span>
                        </div>

                        <div style="font-size: 13px; color: #64748b; margin-bottom: 10px; display: flex; flex-wrap: wrap; gap: 14px;">
                            <span>⌖ {{ $res->facility->lokasi ?? '-' }}</span>
                            <span>📅 {{ \Illuminate\Support\Carbon::parse($res->tanggal)->translatedFormat('l, d F Y') }}</span>
                            <span>⏰ {{ substr($res->start_time, 0, 5) }} - {{ substr($res->end_time, 0, 5) }} WIB</span>
                        </div>

                        <p style="margin: 0; font-size: 13px; color: #475569; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border-left: 3px solid #cbd5e1;">
                            <strong>Tujuan:</strong> {{ $res->tujuan_penggunaan }}
                        </p>

                        @if ($res->cancelled_reason)
                            <p style="margin: 6px 0 0 0; font-size: 12px; color: #ef4444;">
                                <em>Catatan: {{ $res->cancelled_reason }}</em>
                            </p>
                        @endif
                    </div>

                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                        @if ($res->status === 'approved')
                            <span style="background: #dcfce7; color: #166534; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; border: 1px solid #bbf7d0;">
                                Disetujui
                            </span>
                        @elseif ($res->status === 'pending')
                            <span style="background: #fef3c7; color: #92400e; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; border: 1px solid #fde68a;">
                                Menunggu Persetujuan
                            </span>

                            <form method="POST" action="{{ route('reservations.cancel', $res->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan reservasi ini?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: underline; padding: 0;">
                                    Batalkan Pengajuan
                                </button>
                            </form>
                        @elseif ($res->status === 'rejected')
                            <span style="background: #fee2e2; color: #991b1b; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; border: 1px solid #fecaca;">
                                Ditolak
                            </span>
                        @else
                            <span style="background: #f1f5f9; color: #64748b; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 600;">
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="search-card" style="text-align: center; padding: 48px 24px; border-radius: 14px; border: 1px dashed #cbd5e1; background: #fafafa;">
                    <div style="font-size: 40px; margin-bottom: 12px;">📋</div>
                    <h4 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 700; color: #334155;">Belum Ada Pengajuan Reservasi</h4>
                    <p style="margin: 0 0 18px 0; font-size: 13px; color: #64748b; max-width: 400px; margin-left: auto; margin-right: auto;">
                        Anda belum pernah mengajukan peminjaman fasilitas kampus. Klik tombol di bawah untuk membuat reservasi ruangan atau alat.
                    </p>
                    <button type="button" onclick="toggleForm()" style="border: none; border-radius: 8px; padding: 10px 18px; background: #2563eb; color: white; font-size: 13px; font-weight: 600; cursor: pointer;">
                        + Buat Pengajuan Sekarang
                    </button>
                </div>
            @endforelse

            <div style="margin-top: 14px;">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>

    <script>
        function toggleForm() {
            const wrapper = document.getElementById('reservationFormWrapper');
            const text = document.getElementById('toggleButtonText');
            if (wrapper.style.display === 'none' || wrapper.style.display === '') {
                wrapper.style.display = 'block';
                text.innerText = 'Tutup Formulir';
                wrapper.scrollIntoView({ behavior: 'smooth' });
            } else {
                wrapper.style.display = 'none';
                text.innerText = 'Ajukan Peminjaman Baru';
            }
        }
    </script>
@endsection
