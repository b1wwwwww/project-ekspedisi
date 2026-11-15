<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zona_tarif', function (Blueprint $table) {
            $table->unsignedTinyInteger('kode_zona') // Membuat kolom angka kecil tanpa nilai negatif.
                ->nullable()
                ->unique() 
                ->after('id'); // Mengatur posisi kolom kode_zona
        });
    }

    public function down(): void
    {
        Schema::table('zona_tarif', function (Blueprint $table) {
            // menghapus indeks unik dan kolom kode_zona.
            $table->dropUnique(['kode_zona']); 
            $table->dropColumn('kode_zona');  
        });
    }
};
