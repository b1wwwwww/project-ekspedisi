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
        Schema::create('detail_setoran_cod', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setoran_id')->constrained('setoran_cod'); //Menghubungkan setiap detail dengan transaksi setoran COD.
            $table->foreignId('paket_id')->unique()->constrained('paket');
            $table->decimal('jumlah_cod', 12, 2);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_setoran_cod');
    }
};
