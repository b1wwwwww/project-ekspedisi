<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama',
            'email',
            'telepon',
            'password',
            'role',
            'wajib_ganti_password',
            ])]

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'wajib_ganti_password' => 'boolean',
        ];
    }

    public function zonaTarif()
    {
        // Setiap pengguna dapat mengubah banyak data zona tarif.
        return $this->hasMany(ZonaTarif::class, 'diubah_oleh'); 
    }

    public function kurir()
    {
        // Satu akun pengguna dapat memiliki satu profil kurir.
        return $this->hasOne(Kurir::class, 'pengguna_id'); 
    }

    public function paket()
    {
        // Setiap pengguna dapat membuat banyak data paket.
        return $this->hasMany(Paket::class, 'dibuat_oleh'); 
    }

    public function riwayatPaket()
    {
        // satu pengguna bisa mencatat banyak perubahan status paket.
        return $this->hasMany(RiwayatPaket::class, 'pengguna_id');
    }

    public function setoranCod()
    {
        // Satu pengguna (misalnya Admin) bisa mencatat banyak transaksi setoran COD.
        return $this->hasMany(SetoranCod::class, 'dicatat_oleh');
    }
    
}
