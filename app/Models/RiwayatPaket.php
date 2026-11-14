<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPaket extends Model
{
    protected $table = 'riwayat_paket';

    const UPDATED_AT = null;

    protected $fillable = [
        'paket_id',
        'pengguna_id',
        'status_dari',
        'status',
        'catatan'
    ];

    public function paket()
    {
        // Setiap riwayat berkaitan dengan satu paket.
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function pengguna()
    {
        // Setiap riwayat dicatat oleh satu akun pengguna.
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
