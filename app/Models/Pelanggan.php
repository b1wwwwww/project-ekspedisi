<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'nama', 
        'telepon',
        'alamat',
    ];

    public function paket()
    {
        // setiap satu pelanggan dapat memiliki banyak paket melalui kolom pengirim_id.
        return $this->hasMany(Paket::class, 'pengirim_id');
    }
}
