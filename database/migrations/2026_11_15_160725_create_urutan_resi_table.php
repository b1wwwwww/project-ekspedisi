<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('urutan_resi', function (Blueprint $table) {
            $table->date('tanggal')->primary();
            $table->unsignedInteger('nomor_terakhir')->default(0);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('urutan_resi');
    }
};
