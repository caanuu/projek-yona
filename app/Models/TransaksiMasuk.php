<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'supplier_id',      // Menggantikan kolom 'supplier' (string)
        'user_id',          // Menggantikan 'pegawai_penerima'
        'qty',
        'keterangan_masuk'
    ];

    // Relasi ke DetailBarang
    public function details()
    {
        return $this->hasMany(DetailBarang::class);
    }

    public function detailBarangs()
    {
        return $this->hasMany(DetailBarang::class, 'transaksi_masuk_id');
    }

    // Relasi ke Supplier (Master Data)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke User (Petugas Gudang yang input)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
