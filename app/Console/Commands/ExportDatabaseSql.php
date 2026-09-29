<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportDatabaseSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export-sql {--file= : Path berkas output SQL}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ekspor skema DDL dan data database aktif ke format .sql standar untuk pengumpulan berkas UTS PPK 2026';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai proses ekspor database...');

        $filePath = $this->option('file') ?: database_path('ppk2026_reservasi_fasilitas.sql');
        $dbName = config('database.connections.mysql.database', 'ppk2026_reservasi_fasilitas');
        $timestamp = now()->toDateTimeString();

        $pdo = DB::connection()->getPdo();

        $header = "-- -------------------------------------------------------------\n"
            ."-- Proyek: Sistem Reservasi & Pelaporan Fasilitas Kampus (PPK 2026)\n"
            ."-- Berkas: Dump Database Lengkap (Skema DDL & Data Seeder)\n"
            ."-- Basis Data: {$dbName}\n"
            ."-- Target DBMS: MySQL 8.x / MariaDB (utf8mb4_unicode_ci)\n"
            ."-- Tanggal Pembuatan: {$timestamp} WIB\n"
            ."-- Penanggung Jawab Modul: Menza Isaiah Tampubolon & Joshua Satria Kusuma\n"
            ."-- -------------------------------------------------------------\n\n"
            ."SET FOREIGN_KEY_CHECKS = 0;\n"
            ."SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n"
            ."SET time_zone = \"+00:00\";\n"
            ."SET NAMES utf8mb4;\n\n";

        $sqlContent = $header;

        // Ambil seluruh daftar tabel khusus database aktif saat ini
        $rawTables = DB::select('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_TYPE = "BASE TABLE" ORDER BY TABLE_NAME');
        $tables = array_map(fn ($row) => $row->TABLE_NAME, $rawTables);

        $totalTables = count($tables);
        $totalRows = 0;

        foreach ($tables as $table) {
            $this->line("• Mengekspor tabel: <info>{$table}</info>");

            $sqlContent .= "-- -------------------------------------------------------------\n";
            $sqlContent .= "-- Struktur Tabel `{$table}`\n";
            $sqlContent .= "-- -------------------------------------------------------------\n";
            $sqlContent .= "DROP TABLE IF EXISTS `{$table}`;\n";

            $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
            if (! empty($createResult)) {
                $createRow = (array) $createResult[0];
                $createSql = $createRow['Create Table'] ?? array_values($createRow)[1] ?? null;
                if ($createSql) {
                    $sqlContent .= $createSql.";\n\n";
                }
            }

            // Ambil data baris
            $rows = DB::table($table)->get();
            $rowCount = $rows->count();

            if ($rowCount > 0) {
                $totalRows += $rowCount;
                $sqlContent .= "-- Data untuk tabel `{$table}` ({$rowCount} baris)\n";

                // Chunk insert statements per 50 rows
                $chunks = $rows->chunk(50);
                foreach ($chunks as $chunk) {
                    $columns = array_keys((array) $chunk->first());
                    $quotedColumns = array_map(fn ($col) => "`{$col}`", $columns);

                    $sqlContent .= 'INSERT INTO `'.$table.'` ('.implode(', ', $quotedColumns).") VALUES\n";

                    $valueLines = [];
                    foreach ($chunk as $row) {
                        $values = [];
                        foreach ((array) $row as $val) {
                            if (is_null($val)) {
                                $values[] = 'NULL';
                            } elseif (is_numeric($val) && ! is_string($val)) {
                                $values[] = $val;
                            } else {
                                $values[] = $pdo->quote((string) $val);
                            }
                        }
                        $valueLines[] = '('.implode(', ', $values).')';
                    }

                    $sqlContent .= implode(",\n", $valueLines).";\n";
                }
                $sqlContent .= "\n";
            }
        }

        $sqlContent .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        $sqlContent .= "-- Selesai diekspor pada {$timestamp} WIB\n";

        file_put_contents($filePath, $sqlContent);

        $fileSizeKb = round(filesize($filePath) / 1024, 2);

        $this->newLine();
        $this->info("✓ Sukses! Berkas SQL tersimpan di: {$filePath}");
        $this->info("✓ Rincian: {$totalTables} tabel, {$totalRows} baris data, ukuran {$fileSizeKb} KB.");

        return Command::SUCCESS;
    }
}
