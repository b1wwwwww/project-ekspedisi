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
        Schema::create('zona_tarif', function (Blueprint $table) {
            $table->id();
            $table->string('nama_zona');
            $table->decimal('harga_per_kg', 12, 2);
            $table->text('cakupan_wilayah');
            $table->foreignId('diubah_oleh')->nullable()->constrained('users'); // nullable() karena data zona tarif mungkin dibuat melalui seeder, bukan selalu melalui tindakan pengguna.
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zona_tarif');
    }
};
