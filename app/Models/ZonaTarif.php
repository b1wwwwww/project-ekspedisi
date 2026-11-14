<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZonaTarif extends Model
{
    protected $table = 'zona_tarif';

    protected $fillable = [
        'nama_zona',
        'harga_per_kg',
        'cakupan_wilayah',
        'diubah_oleh',
    ];

    public function pengubah()
    {
        // setiap data zona tarif terhubung dengan satu akun pengguna melalui kolom diubah_oleh.
        return $this->belongsTo(User::class, 'diubah_oleh'); 
    }

    public function paket()
    {
        // Satu zona tarif dapat digunakan oleh banyak paket
        return $this->hasMany(Paket::class, 'zona_tarif_id');
    }
}
