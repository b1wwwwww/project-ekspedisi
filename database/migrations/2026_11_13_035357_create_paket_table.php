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
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->string('no_resi')->unique();
            $table->date('tanggal_masuk');
            $table->foreignId('pengirim_id')->constrained('pelanggan');
            $table->foreignId('kurir_id')->nullable()->constrained('kurir');
            $table->foreignId('dibuat_oleh')->constrained('users');            
            $table->foreignId('zona_tarif_id')->constrained('zona_tarif');
            
            // data penerima di simpan langsung di paket karena 
            // menyimpan informasi penerima sesuai kondisi saat paket dibuat.
            $table->string('penerima_nama');
            $table->string('penerima_telepon', 20);
            $table->text('penerima_alamat');
            $table->string('kota_tujuan');

            // informasi berat dan tarif
            $table->decimal('berat_kg', 8, 2);
            $table->decimal('berat_tagih', 8, 2);
            $table->decimal('tarif_per_kg', 12, 2);
            $table->decimal('ongkir', 12, 2);

            // informasi layanan dan COD
            $table->string('layanan');
            $table->decimal('nilai_cod', 12, 2)->default(0);
            $table->decimal('biaya_cod', 12, 2)->default(0);

            // status dan verifikasi 
            $table->string('status')->default('Diterima');
            $table->unsignedInteger('jumlah_gagal')->default(0);
            $table->string('zona_manual')->nullable();
            $table->boolean('perlu_verifikasi')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};
