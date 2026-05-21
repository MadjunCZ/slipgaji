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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint', 255)->index();
            $table->enum('method', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])->default('GET');
            $table->json('params')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->unsignedSmallInteger('status_code')->default(0);
            $table->json('response_data')->nullable();
            $table->float('execution_time_ms', 10, 2)->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['endpoint', 'created_at']);
            $table->index(['method', 'created_at']);
            $table->index(['status_code', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
