<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'keterangan',
        'stok_baik',
        'stok_rusak',
        'harga'
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi ke Mutasi Kondisi (INI YANG MENYEBABKAN ERROR SEBELUMNYA)
    public function mutasiKondisis()
    {
        return $this->hasMany(MutasiKondisi::class);
    }

    // Relasi ke Detail Transaksi (Masuk/Keluar)
    public function details()
    {
        return $this->hasMany(DetailBarang::class);
    }
}
