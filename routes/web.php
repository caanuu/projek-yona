<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\MutasiKondisiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- RUTE LOGIN ---
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // --- HOME REDIRECT ---
    Route::get('/home', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role === 'admin')
            return redirect()->route('dashboard');
        if ($user->role === 'kasir')
            return redirect()->route('transaksi-keluar.index');
        if ($user->role === 'gudang')
            return redirect()->route('transaksi-masuk.index');
        return redirect()->route('login');
    })->name('home');

    // --- GLOBAL ---
    Route::middleware('role:admin,gudang,kasir')->group(function () {
        Route::get('barang', [BarangController::class, 'index'])->name('barang.index');
    });

    // --- ADMIN & GUDANG ---
    Route::middleware('role:admin,gudang')->group(function () {
        Route::resource('supplier', SupplierController::class);

        Route::get('barang/create', [BarangController::class, 'create'])->name('barang.create');
        Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
        Route::get('barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('barang/{barang}', [BarangController::class, 'update'])->name('barang.update');

        Route::get('transaksi-masuk', [TransaksiMasukController::class, 'index'])->name('transaksi-masuk.index');
        Route::get('transaksi-masuk/create', [TransaksiMasukController::class, 'create'])->name('transaksi-masuk.create');
        Route::post('transaksi-masuk', [TransaksiMasukController::class, 'store'])->name('transaksi-masuk.store');
        Route::get('/transaksi-masuk/export', [TransaksiMasukController::class, 'export'])->name('transaksi-masuk.export');

        Route::resource('mutasi-kondisi', MutasiKondisiController::class)->only(['create', 'store']);
        Route::get('list', [BarangController::class, 'list'])->name('barang.list');
        Route::get('rusak', [BarangController::class, 'rusak'])->name('barang.rusak');
    });

    // --- ADMIN & KASIR (FITUR KASIR LENGKAP) ---
    Route::middleware('role:admin,kasir')->group(function () {
        // Resource route mengcover: index, create, store, edit, update, destroy
        Route::resource('transaksi-keluar', TransaksiKeluarController::class);

        Route::get('/transaksi-keluar/export', [TransaksiKeluarController::class, 'export'])->name('transaksi-keluar.export');
        Route::get('/transaksi-keluar/{id}/print', [TransaksiKeluarController::class, 'print'])->name('transaksi-keluar.print');

        Route::get('/get-stok-barang/{id}', [BarangController::class, 'getStok'])->name('barang.getStok');
    });

    // --- ADMIN ONLY ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    });
});
