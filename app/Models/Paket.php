<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';

    protected $fillable = [
        'no_resi',
        'tanggal_masuk',
        'pengirim_id',
        'kurir_id',
        'dibuat_oleh',
        'zona_tarif_id',
        'penerima_nama',
        'penerima_telepon',
        'penerima_alamat',
        'kota_tujuan',
        'berat_kg',
        'berat_tagih',
        'tarif_per_kg',
        'ongkir',
        'layanan',
        'nilai_cod',
        'biaya_cod',
        'status',
        'jumlah_gagal',
        'zona_manual',
        'perlu_verifikasi',
    ];

    public function pengirim()
    {
        // setiap data paket terhubung dengan satu pelanggan melalui kolom pengirim_id.
        return $this->belongsTo(Pelanggan::class, 'pengirim_id'); 
    }

    public function kurir()
    {
        // setiap data paket terhubung dengan satu kurir melalui kolom kurir_id.
        return $this->belongsTo(Kurir::class, 'kurir_id'); 
    }

    public function pembuat()
    {
        // setiap data paket terhubung dengan satu akun pengguna melalui kolom dibuat_oleh.
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function zonaTarif()
    {
        // setiap data paket terhubung dengan satu zona tarif melalui kolom zona_tarif_id.
        return $this->belongsTo(ZonaTarif::class, 'zona_tarif_id');
    }

    public function detailSetoranCod()
    {
        // satu paket maksimal memiliki satu detail setoran COD.
        return $this->hasOne(DetailSetoranCod::class, 'paket_id');
    }

    public function riwayat()
    {
        // satu paket bisa mengalami banyak perubahan status
        return $this->hasMany(RiwayatPaket::class, 'paket_id');
    }

}
