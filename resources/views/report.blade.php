@extends('layouts.app')

@section('title', 'Pelaporan')
@section('page-title', 'Pelaporan')

@section('content')
    <div class="section-header">
        <div>
            <h2>Form Pelaporan Fasilitas</h2>
            <p>Laporkan kerusakan atau masalah pada fasilitas kampus.</p>
        </div>
    </div>

    <div class="search-card" style="max-width: 760px;">
        <form method="POST" action="/reports" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Kategori Fasilitas</label>
                <select id="reportFacilityCategory" name="facility_category" required style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
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
                <select id="reportFacilitySpec" name="facility_name" required disabled style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
                    <option value="">-- Pilih spesifikasi --</option>
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Kategori</label>
                <select name="category" required style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
                    <option value="kerusakan">Kerusakan</option>
                    <option value="kebersihan">Kebersihan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Deskripsi</label>
                <textarea name="description" rows="4" required style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; resize: vertical; background: white;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Foto (opsional)</label>
                <input name="photo" type="file" style="width: 100%; padding: 12px 14px; border: 1px solid #dbe1ea; border-radius: 10px; background: white;">
            </div>

            <button type="submit" style="border: none; border-radius: 10px; padding: 12px 20px; background: #f59e0b; color: white; font-weight: 600; cursor: pointer;">
                Kirim Laporan
            </button>
        </form>
    </div>

    <p style="color: #64748b; margin-top: 16px;">Lampirkan foto jika memungkinkan (kecuali foto kucing yang tidak relevan).</p>

    <script>
        const reportFacilityOptions = {
            'Ruang Kelas': ['E103', 'A301', 'A302', 'A303', 'A304', 'A305'],
            'Aula': ['Aula A', 'Aula B'],
            'Laboratorium': ['Lab A', 'Lab B', 'Lab C', 'Lab D'],
            'Alat': ['Meja Kelas', 'Meja Panjang', 'Kursi Kelas', 'Kursi Panjang', 'Sound System', 'Mic', 'TV'],
            'Lapangan': ['Lapangan Voli', 'Lapangan Basket']
        };

        const reportFacilityCategory = document.getElementById('reportFacilityCategory');
        const reportFacilitySpec = document.getElementById('reportFacilitySpec');

        function updateReportSpecOptions() {
            const category = reportFacilityCategory.value;
            const options = reportFacilityOptions[category] || [];

            reportFacilitySpec.innerHTML = '<option value="">-- Pilih spesifikasi --</option>' +
                options.map(item => `<option value="${item}">${item}</option>`).join('');

            reportFacilitySpec.disabled = options.length === 0;
        }

        reportFacilityCategory.addEventListener('change', updateReportSpecOptions);
    </script>
@endsection
