<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Supplier;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Kategori
        Kategori::insert([
            ['nama_kategori' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Perabotan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Bahan Baku', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Seed Supplier
        Supplier::insert([
            ['nama_supplier' => 'PT. Sumber Makmur', 'alamat' => 'Jl. Raya No. 1, Jakarta', 'telp' => '08123456789', 'created_at' => now(), 'updated_at' => now()],
            ['nama_supplier' => 'CV. Abadi Jaya', 'alamat' => 'Jl. Kebon Jeruk No. 5, Bandung', 'telp' => '08987654321', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Users (Admin, Gudang, Kasir) dengan Username
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@yona.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Staf Gudang',
            'username' => 'gudang',
            'email' => 'gudang@yona.com',
            'password' => Hash::make('password123'),
            'role' => 'gudang',
        ]);

        User::create([
            'name' => 'Staf Kasir',
            'username' => 'kasir',
            'email' => 'kasir@yona.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);

        // Seed Barang Awal (Opsional, agar tidak kosong saat tes)
        // Pastikan id kategori 1 & 2 sudah ada dari insert di atas
        \App\Models\Barang::create([
            'kode_barang' => 'ELK-001',
            'nama_barang' => 'Kabel HDMI',
            'kategori_id' => 1,
            'keterangan' => 'Kabel HDMI 2 Meter',
            'stok_baik' => 10,
            'stok_rusak' => 0,
            'harga' => 50000
        ]);
    }
}
