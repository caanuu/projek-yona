<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABEL KATEGORI (Sesuai Tabel 3.4 [cite: 18, 19])
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id(); // id_kategori
            $table->string('nama_kategori', 100);
            $table->timestamps();
        });

        // 2. TABEL SUPPLIER (Sesuai Tabel 3.6 [cite: 22, 23])
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // id_supplier
            $table->string('nama_supplier', 150);
            $table->text('alamat')->nullable();
            $table->string('telp', 20)->nullable();
            $table->timestamps();
        });

        // 3. UPDATE TABEL USERS
        // Dokumen meminta role 'admin' & 'petugas', tapi kita sesuaikan
        // menjadi 'admin', 'gudang', 'kasir' sesuai permintaan Anda.
        Schema::table('users', function (Blueprint $table) {
            // Tambah username (Tabel 3.3 [cite: 16, 17])
            $table->string('username', 50)->unique()->after('id')->nullable();

            // Pastikan kolom role mendukung enum yang diinginkan
            // Jika kolom role sudah ada (varchar), kita biarkan, validasi di level aplikasi
        });

        // 4. UPDATE TABEL BARANGS (Sesuai Tabel 3.5 [cite: 20, 21])
        Schema::table('barangs', function (Blueprint $table) {
            // Hapus kolom lama yang digantikan
            if (Schema::hasColumn('barangs', 'jenis_barang')) {
                $table->dropColumn('jenis_barang');
            }

            // Tambah Foreign Key Kategori
            $table->foreignId('kategori_id')->nullable()->after('nama_barang')
                  ->constrained('kategoris')->onDelete('set null');

            // Tambah kolom harga (jual) fix di master barang
            if (!Schema::hasColumn('barangs', 'harga')) {
                $table->decimal('harga', 12, 2)->default(0)->after('stok_rusak');
            }
        });

        // 5. UPDATE TRANSAKSI MASUK (Sesuai Tabel 3.7 [cite: 24, 25])
        Schema::table('transaksi_masuks', function (Blueprint $table) {
            // Hapus kolom manual
            if (Schema::hasColumn('transaksi_masuks', 'supplier')) {
                $table->dropColumn('supplier');
            }
            if (Schema::hasColumn('transaksi_masuks', 'pegawai_penerima')) {
                $table->dropColumn('pegawai_penerima');
            }

            // Ganti dengan Relasi
            $table->foreignId('supplier_id')->nullable()->after('kode_transaksi')
                  ->constrained('suppliers');

            // User yang menginput (Gudang)
            $table->foreignId('user_id')->nullable()->after('supplier_id')
                  ->constrained('users');
        });

        // 6. UPDATE TRANSAKSI KELUAR (Sesuai Tabel 3.9 [cite: 28, 29])
        Schema::table('transaksi_keluars', function (Blueprint $table) {
            // User yang menginput (Kasir)
            $table->foreignId('user_id')->nullable()->after('kode_transaksi')
                  ->constrained('users');

            // Dokumen tidak mencatat 'customer' di tabel transaksi keluar,
            // tapi jika Anda butuh mencatat nama pembeli, biarkan kolom 'customer'.
            // Jika ingin strik sesuai doc, hapus kolom customer.
            // $table->dropColumn('customer');
        });
    }

    public function down(): void
    {
        // Rollback logic...
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('kategoris');
    }
};
