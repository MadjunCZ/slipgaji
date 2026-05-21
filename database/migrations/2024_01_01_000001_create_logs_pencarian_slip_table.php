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
        Schema::create('logs_pencarian_slip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nip', 50)->nullable()->index();
            $table->unsignedTinyInteger('bulan')->nullable();
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->string('unit_kerja', 100)->nullable();
            $table->string('tujuan_unduh', 100)->nullable();
            $table->enum('status', ['success', 'failed', 'error'])->default('failed');
            $table->json('response_data')->nullable();
            $table->text('error_message')->nullable();
            $table->float('execution_time_ms', 10, 2)->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes untuk query yang sering digunakan
            $table->index(['user_id', 'created_at']);
            $table->index(['nip', 'bulan', 'tahun']);
            $table->index(['status', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_pencarian_slip');
    }
};
