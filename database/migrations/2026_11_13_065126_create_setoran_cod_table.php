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
        Schema::create('setoran_cod', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurir_id')->constrained('kurir');
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->dateTime('tanggal_setoran');
            $table->decimal('total_setoran', 12, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_cod');
    }
};
