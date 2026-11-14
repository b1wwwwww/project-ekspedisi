<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranCod extends Model
{
    protected $table = 'setoran_cod';

    protected $fillable = [
        'kurir_id',
        'dicatat_oleh',
        'tanggal_setoran',
        'total_setoran',
        'catatan'
    ];

    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
        // kurir_id menunjukkan siapa yang menyetorkan uang
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
        // dicatat_oleh menunjukkan siapa yang mencatat setoran
    }

    public function detail()
    {
        // satu transaksi setoran COD bisa memiliki banyak detail paket.
        return $this->hasMany(DetailSetoranCod::class, 'setoran_id');
    }
}
