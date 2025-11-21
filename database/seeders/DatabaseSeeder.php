<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Barang;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Kategori (Gunakan firstOrCreate agar tidak duplikat)
        $katElektronik = Kategori::firstOrCreate(['nama_kategori' => 'Elektronik']);
        Kategori::firstOrCreate(['nama_kategori' => 'Perabotan']);
        Kategori::firstOrCreate(['nama_kategori' => 'Bahan Baku']);

        // 2. Seed Supplier
        Supplier::firstOrCreate(
            ['nama_supplier' => 'PT. Sumber Makmur'],
            ['alamat' => 'Jl. Raya No. 1, Jakarta', 'telp' => '08123456789']
        );
        Supplier::firstOrCreate(
            ['nama_supplier' => 'CV. Abadi Jaya'],
            ['alamat' => 'Jl. Kebon Jeruk No. 5, Bandung', 'telp' => '08987654321']
        );

        // 3. Seed Users (Gunakan updateOrInsert agar aman saat di-run ulang)
        User::updateOrInsert(
            ['email' => 'admin@yona.com'], // Cek berdasarkan email
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        User::updateOrInsert(
            ['email' => 'gudang@yona.com'],
            [
                'name' => 'Staf Gudang',
                'username' => 'gudang',
                'password' => Hash::make('password123'),
                'role' => 'gudang',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        User::updateOrInsert(
            ['email' => 'kasir@yona.com'],
            [
                'name' => 'Staf Kasir',
                'username' => 'kasir',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 4. Seed Barang Awal
        Barang::updateOrInsert(
            ['kode_barang' => 'ELK-001'],
            [
                'nama_barang' => 'Kabel HDMI',
                'kategori_id' => $katElektronik->id, // Gunakan ID dari variabel di atas
                'keterangan' => 'Kabel HDMI 2 Meter',
                'stok_baik' => 10,
                'stok_rusak' => 0,
                'harga' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
