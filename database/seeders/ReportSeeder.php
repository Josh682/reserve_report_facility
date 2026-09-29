<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan direktori public disk 'reports' tersedia dan sediakan sampel foto dummy
        $this->ensureSampleImagesExist();

        $pengguna = User::where('role', 'pengguna')->where('status_akun', 'verified')->first();
        $petugas = User::where('role', 'petugas')->where('status_akun', 'verified')->first();

        if (! $pengguna) {
            $pengguna = User::factory()->pengguna()->verified()->create([
                'name' => 'Mahasiswa Contoh',
                'email' => 'mahasiswa@kampus.test',
            ]);
        }

        if (! $petugas) {
            $petugas = User::factory()->petugas()->verified()->create([
                'name' => 'Petugas Fasilitas',
                'email' => 'petugas@kampus.test',
            ]);
        }

        // Ambil referensi fasilitas
        $ruangA101 = Facility::where('nama', 'Ruang A101')->first();
        $labA = Facility::where('nama', 'Lab Komputer A')->first();
        $aulaUtama = Facility::where('nama', 'Aula Utama Kampus')->first();
        $ruangB203 = Facility::where('nama', 'Ruang B203')->first();
        $lapBasket = Facility::where('nama', 'Lapangan Basket')->first();
        $labC = Facility::where('nama', 'Lab Komputer C')->first();
        $aulaFsm = Facility::where('nama', 'Aula FSM')->first();
        $alatSound = Facility::where('nama', 'Sound System Portable & Wireless Mic')->first();

        $reports = [
            // 1. Ruang A101 - Baru dengan Foto
            [
                'facility_id' => $ruangA101?->id,
                'kategori' => 'kerusakan',
                'deskripsi' => 'Proyektor plafon sering mati mendadak setelah 15 menit pemakaian dan lampu indikator error berkedip merah saat kuliah berlangsung.',
                'foto_path' => 'reports/sample-proyektor.png',
                'status' => 'baru',
                'catatan_resolusi' => null,
                'resolved_by' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            // 2. Lab Komputer A - Diproses dengan Foto
            [
                'facility_id' => $labA?->id,
                'kategori' => 'kerusakan',
                'deskripsi' => 'Unit pendingin ruangan AC di baris belakang meneteskan air ke meja komputer PC-12 dan PC-13 sehingga membahayakan peralatan listrik.',
                'foto_path' => 'reports/sample-ac.png',
                'status' => 'diproses',
                'catatan_resolusi' => 'Teknisi pendingin ruangan sedang membongkar saluran pipa drainase dan melakukan pembersihan filter evaporator.',
                'resolved_by' => $petugas->id,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(1),
            ],
            // 3. Aula Utama Kampus - Selesai dengan Foto
            [
                'facility_id' => $aulaUtama?->id,
                'kategori' => 'kerusakan',
                'deskripsi' => 'Kabel konektor HDMI panggung utama putus dan jack mikrofon wireless channel 2 berderik kencang saat digerakkan.',
                'foto_path' => 'reports/sample-sound.png',
                'status' => 'selesai',
                'catatan_resolusi' => 'Kabel konektor HDMI telah diganti dengan kabel baru berisolasi tinggi dan jack receiver mic wireless telah disolder ulang serta lolos uji suara.',
                'resolved_by' => $petugas->id,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(3),
            ],
            // 4. Ruang B203 - Ditolak (Tanpa Foto)
            [
                'facility_id' => $ruangB203?->id,
                'kategori' => 'lainnya',
                'deskripsi' => 'Permintaan penambahan sofa santai dan dispenser air minum galon di sudut belakang ruang kelas B203 untuk relaksasi mahasiswa.',
                'foto_path' => null,
                'status' => 'ditolak',
                'catatan_resolusi' => 'Laporan ditolak. Ruang B203 diperuntukkan murni sebagai ruang perkuliahan teori standar, usulan fasilitas tambahan disarankan melalui proposal perlengkapan BAA.',
                'resolved_by' => $petugas->id,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(9),
            ],
            // 5. Lapangan Basket - Selesai (Kebersihan, Tanpa Foto)
            [
                'facility_id' => $lapBasket?->id,
                'kategori' => 'kebersihan',
                'deskripsi' => 'Sisa botol minuman plastik dan sampah bungkus makanan berserakan di tribun penonton barat setelah pertandingan sparing kemarin sore.',
                'foto_path' => null,
                'status' => 'selesai',
                'catatan_resolusi' => 'Petugas kebersihan telah mengangkut sampah di seluruh area tribun barat dan menempatkan dua tong sampah tambahan di samping lapangan.',
                'resolved_by' => $petugas->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            // 6. Lab Komputer C - Diproses (Tanpa Foto)
            [
                'facility_id' => $labC?->id,
                'kategori' => 'kerusakan',
                'deskripsi' => 'Tiga unit mouse optik pada meja PC-05, PC-06, dan PC-08 scroll wheel-nya macet dan klik kiri tidak responsif saat praktikum.',
                'foto_path' => null,
                'status' => 'diproses',
                'catatan_resolusi' => 'Penggantian 3 unit mouse sedang menunggu alokasi periferal pengganti dari gudang perlengkapan IT.',
                'resolved_by' => $petugas->id,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subHours(5),
            ],
            // 7. Aula FSM - Baru dengan Foto
            [
                'facility_id' => $aulaFsm?->id,
                'kategori' => 'kerusakan',
                'deskripsi' => 'Lampu sorot LED di sisi kanan panggung utama mati total sehingga area pembicara tampak gelap saat gladi resik seminar.',
                'foto_path' => 'reports/sample-lampu.png',
                'status' => 'baru',
                'catatan_resolusi' => null,
                'resolved_by' => null,
                'created_at' => now()->subHours(8),
                'updated_at' => now()->subHours(8),
            ],
            // 8. Alat Sound System - Baru (Tanpa Foto)
            [
                'facility_id' => $alatSound?->id,
                'kategori' => 'kerusakan',
                'deskripsi' => 'Baterai rechargeable microphone wireless habis sangat cepat (< 15 menit) dan charger portable unit terasa sangat panas saat diisi daya.',
                'foto_path' => null,
                'status' => 'baru',
                'catatan_resolusi' => null,
                'resolved_by' => null,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
        ];

        foreach ($reports as $data) {
            if ($data['facility_id']) {
                Report::updateOrCreate(
                    [
                        'facility_id' => $data['facility_id'],
                        'deskripsi' => $data['deskripsi'],
                    ],
                    array_merge($data, ['user_id' => $pengguna->id])
                );
            }
        }
    }

    /**
     * Memastikan file sampel foto dummy tersedia di storage public disk.
     */
    protected function ensureSampleImagesExist(): void
    {
        $disk = Storage::disk('public');

        if (! $disk->exists('reports')) {
            $disk->makeDirectory('reports');
        }

        // Sampel PNG valid 100x100 pixel dengan variasi warna untuk thumbnail yang menarik
        $sampleImages = [
            'reports/sample-proyektor.png' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAiSURBVHhe7cEBDQAAAMKg909tDwcUAAAAAAAAAAAAAAAAPg0hGAABjQ+o1QAAAABJRU5ErkJggg==',
            'reports/sample-ac.png' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAiSURBVHhe7cEBDQAAAMKg909tDwcUAAAAAAAAAAAAAAAAPg0hGAABjQ+o1QAAAABJRU5ErkJggg==',
            'reports/sample-sound.png' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAiSURBVHhe7cEBDQAAAMKg909tDwcUAAAAAAAAAAAAAAAAPg0hGAABjQ+o1QAAAABJRU5ErkJggg==',
            'reports/sample-lampu.png' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAiSURBVHhe7cEBDQAAAMKg909tDwcUAAAAAAAAAAAAAAAAPg0hGAABjQ+o1QAAAABJRU5ErkJggg==',
        ];

        foreach ($sampleImages as $path => $base64Data) {
            if (! $disk->exists($path)) {
                $disk->put($path, (string) base64_decode($base64Data));
            }
        }
    }
}
