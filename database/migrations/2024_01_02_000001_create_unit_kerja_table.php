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
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->comment('Kode unik unit kerja');
            $table->string('nama')->comment('Nama lengkap unit kerja');
            $table->enum('jenis', ['sub_bagian', 'seksi', 'kua', 'man', 'min', 'mtsn'])->nullable()->comment('Jenis unit kerja');
            $table->string('kategori')->nullable()->comment('Kategori: Kantor Pusat, KUA, Madrasah, dll');
            $table->boolean('aktif')->default(true)->comment('Status aktif');
            $table->integer('urutan')->default(0)->comment('Urutan tampil');
            $table->timestamps();
            
            // Index untuk performa
            $table->index('jenis');
            $table->index('aktif');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kerja');
    }
};
