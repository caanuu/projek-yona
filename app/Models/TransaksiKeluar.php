<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'qty',
        'customer', // Opsional: Jika masih ingin catat nama pembeli manual
        'user_id',  // Tambahan: Relasi ke Kasir
        'keterangan_keluar'
    ];

    public function details()
    {
        return $this->hasMany(DetailBarang::class, 'transaksi_keluar_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
