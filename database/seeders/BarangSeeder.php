<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori sudah di-seed di DatabaseSeeder sebelum ini
        // ID 1: Elektronik, ID 2: Perabotan, ID 3: Bahan Baku (Sesuai DatabaseSeeder)

        DB::table('barangs')->updateOrInsert(
            ['kode_barang' => 'Gelas-001'],
            [
                'nama_barang' => 'Gelas Plastik',
                'kategori_id' => 2, // ID 2 = Perabotan
                'keterangan' => 'Gelas plastik bening',
                'stok_baik' => 50,
                'stok_rusak' => 0,
                'harga' => 5000, // Harga master (Sesuai Tabel 3.5)
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        DB::table('barangs')->updateOrInsert(
            ['kode_barang' => 'Piring-002'],
            [
                'nama_barang' => 'Piring Kaca',
                'kategori_id' => 2, // ID 2 = Perabotan
                'keterangan' => 'Piring kaca bulat',
                'stok_baik' => 30,
                'stok_rusak' => 2,
                'harga' => 15000, // Harga master
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        DB::table('barangs')->updateOrInsert(
            ['kode_barang' => 'Kabel-003'],
            [
                'nama_barang' => 'Kabel Data Type-C',
                'kategori_id' => 1, // ID 1 = Elektronik
                'keterangan' => 'Kabel fast charging',
                'stok_baik' => 100,
                'stok_rusak' => 5,
                'harga' => 25000,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
