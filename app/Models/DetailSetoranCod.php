<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSetoranCod extends Model
{
    protected $table = 'detail_setoran_cod';

    // Tabel ini tidak memiliki kolom updated_at.
    const UPDATED_AT = null;

    protected $fillable = [
        'setoran_id',
        'paket_id',
        'jumlah_cod'
    ];

    public function setoran()
    {
        // Setiap detail setoran dimiliki oleh satu setoran COD.
        return $this->belongsTo(SetoranCod::class, 'setoran_id');
    }

    public function paket()
    {
        // Setiap detail setoran berkaitan dengan satu paket.
        return $this->belongsTo(Paket::class, 'paket_id');
    }


}
