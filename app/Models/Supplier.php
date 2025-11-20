<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['nama_supplier', 'alamat', 'telp'];

    public function transaksiMasuks()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }
}
