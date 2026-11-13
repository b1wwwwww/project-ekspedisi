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
        Schema::create('riwayat_paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket');
            $table->foreignId('pengguna_id')->constrained('users');
            $table->string('status_dari')->nullable(); // ketika paket pertama kali dibuat, belum ada status sebelumnya.
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_paket');
    }
};
