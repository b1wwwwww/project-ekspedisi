<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    protected $table = 'kurir';

    protected $fillable = [
        'pengguna_id',
        'kode_kurir',
        'area_tugas',
        'aktif',
    ];

    public function pengguna()
    {
        // setiap data kurir terhubung dengan satu akun pengguna melalui kolom pengguna_id.
        return $this->belongsTo(User::class, 'pengguna_id'); 
    }

    public function paket()
    {
        // setiap satu kurir dapat menangani banyak paket melalui kolom kurir_id.
        return $this->hasMany(Paket::class, 'kurir_id'); 
    }

    public function setoranCod()
    {
        // satu kurir bisa melakukan banyak setoran COD.
        return $this->hasMany(SetoranCod::class, 'kurir_id');
    }
}
