@extends('layouts.app')

@section('title', 'Reservasi Saya')
@section('page-title', 'Reservasi Saya')

@section('content')
    <div class="section-header">
        <div>
            <h2>Reservasi Saya</h2>
            <p>Daftar peminjaman fasilitas yang sudah kamu ajukan.</p>
        </div>
    </div>

    <div style="display: grid; gap: 16px; max-width: 900px;">
        <div class="search-card" style="padding: 18px 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div>
                    <strong style="display: block; font-size: 18px; margin-bottom: 4px;">Ruang A101</strong>
                    <span style="color: #64748b; font-size: 13px;">25 Sep 2026 • 09.00 - 10.00</span>
                </div>
                <span style="background: #dcfce7; color: #166534; padding: 7px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">Disetujui</span>
            </div>
        </div>

        <div class="search-card" style="padding: 18px 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div>
                    <strong style="display: block; font-size: 18px; margin-bottom: 4px;">Laboratorium Komputer 2</strong>
                    <span style="color: #64748b; font-size: 13px;">27 Sep 2026 • 13.30 - 15.00</span>
                </div>
                <span style="background: #fef3c7; color: #92400e; padding: 7px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">Menunggu</span>
            </div>
        </div>
    </div>

    <div style="margin-top: 28px; max-width: 900px;">
        <button type="button" id="toggleReservationForm" style="border: none; border-radius: 10px; padding: 12px 18px; background: #2563eb; color: white; font-weight: 600; cursor: pointer;">
            + Buat Reservasi
        </button>
    </div>

    <div id="reservationFormWrapper" style="display: none; margin-top: 28px; max-width: 760px;">
        <div class="section-header" style="margin-top: 0; margin-bottom: 14px;">
            <div>
                <h2>Form Reservasi Baru</h2>
                <p>Ajukan pemakaian fasilitas sesuai kebutuhan aktivitasmu.</p>
            </div>
        </div>

        <div class="search-card" style="max-width: 760px;">
            <form method="POST" action="/reservations">
                @csrf

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Kategori Fasilitas</label>
                    <select id="reservationFacilityCategory" name="facility_category" required style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
                        <option value="">-- Pilih fasilitas --</option>
                        <option value="Ruang Kelas">Ruang Kelas</option>
                        <option value="Aula">Aula</option>
                        <option value="Laboratorium">Laboratorium</option>
                        <option value="Alat">Alat</option>
                        <option value="Lapangan">Lapangan</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Spesifikasi</label>
                    <select id="reservationFacilitySpec" name="facility_name" required disabled style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
                        <option value="">-- Pilih spesifikasi --</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Tanggal</label>
                        <input name="date" type="date" required style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Slot Mulai (30 menit)</label>
                        <input name="start_time" type="time" required style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Tujuan Penggunaan</label>
                    <textarea name="purpose" rows="3" style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; resize: vertical; background: white;"></textarea>
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="submit" style="border: none; border-radius: 10px; padding: 12px 20px; background: #16a34a; color: white; font-weight: 600; cursor: pointer;">
                        Ajukan Reservasi
                    </button>

                    <button type="button" id="cancelReservationForm" style="border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 20px; background: white; color: #475569; font-weight: 600; cursor: pointer;">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <p style="color: #64748b; margin-top: 18px;">Jam operasional 07:00–20:00. Pilih slot 30 menit (07:00, 07:30, dst.). Jangan bawa kucing ke laboratorium.</p>

    <script>
        const reservationFacilityOptions = {
            'Ruang Kelas': ['E103', 'A301', 'A302', 'A303', 'A304', 'A305'],
            'Aula': ['Aula A', 'Aula B'],
            'Laboratorium': ['Lab A', 'Lab B', 'Lab C', 'Lab D'],
            'Alat': ['Meja Kelas', 'Meja Panjang', 'Kursi Kelas', 'Kursi Panjang', 'Sound System', 'Mic', 'TV'],
            'Lapangan': ['Lapangan Voli', 'Lapangan Basket']
        };

        const reservationFacilityCategory = document.getElementById('reservationFacilityCategory');
        const reservationFacilitySpec = document.getElementById('reservationFacilitySpec');
        const toggleButton = document.getElementById('toggleReservationForm');
        const formWrapper = document.getElementById('reservationFormWrapper');
        const cancelButton = document.getElementById('cancelReservationForm');

        function updateReservationSpecOptions() {
            const category = reservationFacilityCategory.value;
            const options = reservationFacilityOptions[category] || [];

            reservationFacilitySpec.innerHTML = '<option value="">-- Pilih spesifikasi --</option>' +
                options.map(item => `<option value="${item}">${item}</option>`).join('');

            reservationFacilitySpec.disabled = options.length === 0;
        }

        reservationFacilityCategory.addEventListener('change', updateReservationSpecOptions);

        if (toggleButton && formWrapper) {
            toggleButton.addEventListener('click', function () {
                formWrapper.style.display = formWrapper.style.display === 'none' ? 'block' : 'none';
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            });
        }

        if (cancelButton && formWrapper) {
            cancelButton.addEventListener('click', function () {
                formWrapper.style.display = 'none';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    </script>
@endsection
