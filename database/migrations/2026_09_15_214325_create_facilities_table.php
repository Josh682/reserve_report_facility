<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->enum('tipe', ['ruang_kelas', 'aula', 'laboratorium', 'alat', 'lapangan']);
            $table->string('lokasi');
            $table->unsignedInteger('kapasitas')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'dalam_perbaikan', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->index('status');
            $table->index('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
