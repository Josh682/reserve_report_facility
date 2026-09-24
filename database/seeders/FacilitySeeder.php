<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'nama' => 'Ruang A101',
                'tipe' => 'ruang_kelas',
                'lokasi' => 'Gedung A, Lantai 1',
                'kapasitas' => 50,
                'deskripsi' => 'Ruang kelas ber-AC dengan proyektor, whiteboard, dan sound system standar.',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Ruang B203',
                'tipe' => 'ruang_kelas',
                'lokasi' => 'Gedung B, Lantai 2',
                'kapasitas' => 35,
                'deskripsi' => 'Ruang kelas kecil, saat ini sedang dalam perbaikan unit pendingin udara.',
                'status' => 'dalam_perbaikan',
            ],
            [
                'nama' => 'Lab Komputer A',
                'tipe' => 'laboratorium',
                'lokasi' => 'Gedung C, Lantai 3',
                'kapasitas' => 40,
                'deskripsi' => 'Lab komputer dengan 40 PC i7, koneksi LAN gigabit, dan proyektor presentasi.',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lab Komputer C',
                'tipe' => 'laboratorium',
                'lokasi' => 'Gedung E, Lantai 2',
                'kapasitas' => 30,
                'deskripsi' => 'Lab multimedia dan komputasi grafis dengan dual monitor.',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Aula Utama Kampus',
                'tipe' => 'aula',
                'lokasi' => 'Gedung Rektorat, Lantai 1',
                'kapasitas' => 300,
                'deskripsi' => 'Aula serbaguna berkapasitas besar untuk seminar, wisuda, dan kuliah umum.',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Aula FSM',
                'tipe' => 'aula',
                'lokasi' => 'Gedung AP, Lantai 4',
                'kapasitas' => 150,
                'deskripsi' => 'Aula fakultas untuk kegiatan rapat akbar, sidang himpunan, dan pameran.',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lapangan Basket',
                'tipe' => 'lapangan',
                'lokasi' => 'Area Olahraga Barat',
                'kapasitas' => 100,
                'deskripsi' => 'Lapangan basket outdoor dengan penerangan lampu malam.',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Sound System Portable & Wireless Mic',
                'tipe' => 'alat',
                'lokasi' => 'Ruang Perlengkapan BAA',
                'kapasitas' => null,
                'deskripsi' => '1 unit active speaker portable 12 inch dan 2 wireless microphone.',
                'status' => 'aktif',
            ],
        ];

        foreach ($facilities as $data) {
            Facility::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }

        // Buat contoh reservasi approved hari ini untuk uji ketersediaan slot (US 1)
        $user = User::where('role', 'pengguna')->first();
        $petugas = User::where('role', 'petugas')->first();
        $ruangA101 = Facility::where('nama', 'Ruang A101')->first();
        $labA = Facility::where('nama', 'Lab Komputer A')->first();

        if ($user && $ruangA101) {
            // Reservasi Ruang A101 slot 08:00 - 10:00 (4 slot x 30 menit)
            Reservation::updateOrCreate(
                [
                    'facility_id' => $ruangA101->id,
                    'tanggal' => now()->toDateString(),
                    'start_time' => '08:00',
                ],
                [
                    'user_id' => $user->id,
                    'end_time' => '10:00',
                    'tujuan_penggunaan' => 'Perkuliahan Algoritma dan Pemrograman',
                    'status' => 'approved',
                    'processed_by' => $petugas?->id,
                ]
            );
        }

        if ($user && $labA) {
            // Reservasi Lab Komputer A slot 13:00 - 15:00 (4 slot x 30 menit)
            Reservation::updateOrCreate(
                [
                    'facility_id' => $labA->id,
                    'tanggal' => now()->toDateString(),
                    'start_time' => '13:00',
                ],
                [
                    'user_id' => $user->id,
                    'end_time' => '15:00',
                    'tujuan_penggunaan' => 'Praktikum Basis Data',
                    'status' => 'approved',
                    'processed_by' => $petugas?->id,
                ]
            );
        }
    }
}
