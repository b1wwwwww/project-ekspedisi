<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;
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
