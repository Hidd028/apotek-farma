<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\RestokObatController;
use App\Http\Controllers\LaporanObatController;
use App\Http\Controllers\LaporanRestokController;
use App\Http\Controllers\LaporanPenjualanController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// 🔹 Hanya bisa diakses kalau belum login
Route::middleware('isLogin')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
});


// 🔹 Logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


// 🔹 Semua yang sudah login bisa akses
Route::middleware('checkLogin')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Obat 
    Route::get('obat', [ObatController::class, 'index'])->name('obat');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])
     ->name('notifikasi.bacaSemua');

    // Data Supplier
    Route::get('supplier', [SupplierController::class, 'index'])->name('supplier');

    // Transaksi Penjualan
    Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan');
    Route::get('penjualan/create', [PenjualanController::class, 'create'])->name('penjualanCreate');
    Route::post('penjualan/store', [PenjualanController::class, 'store'])->name('penjualanStore');

    // Restok Obat
    Route::get('restok', [RestokObatController::class, 'index'])->name('restok');

    // ⚙️ Pengaturan Profil
    Route::get('profil', [UserController::class, 'editProfil'])->name('profilEdit');
    Route::post('/profil/update', [UserController::class, 'updateProfil'])->name('profilUpdate');

    // Laporan Penjualan
    Route::get('laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    Route::get('laporan/generate', [LaporanPenjualanController::class, 'generate'])->name('laporan.generate');
    Route::get('laporan/export/pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('laporan/export/excel', [LaporanPenjualanController::class, 'exportExcel'])->name('laporan.export.excel');

    // Laporan Restok
    Route::get('/laporan/restok', [LaporanRestokController::class, 'index'])->name('laporan.restok');
    Route::get('/laporan/restok/export/excel', [LaporanRestokController::class, 'exportExcel'])->name('laporan.restok.export.excel');
    Route::get('/laporan/restok/export/pdf', [LaporanRestokController::class, 'exportPdf'])->name('laporan.restok.export.pdf');

    // Laporan Obat
    Route::get('/laporan/obat', [LaporanObatController::class, 'index'])->name('laporan.obat');
    Route::get('/laporan/obat/export/pdf', [LaporanObatController::class, 'exportPdf'])->name('laporan.obat.export.pdf');
    Route::get('/laporan/obat/export/excel', [LaporanObatController::class, 'exportExcel'])->name('laporan.obat.export.excel');

});


// 🔹 Hanya Admin yang bisa mengelola user
Route::middleware('isAdmin')->group(function () {

    // CRUD User
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user/create', [UserController::class, 'create'])->name('userCreate');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('userEdit');
    Route::post('user/update/{id}', [UserController::class, 'update'])->name('userUpdate');
    Route::post('user/store', [UserController::class, 'store'])->name('userStore');
    Route::delete('user/destroy/{id}', [UserController::class, 'destroy'])->name('userDestroy');
    // Ekspor
    Route::get('user/excel', [UserController::class, 'excel'])->name('userExcel');
    Route::get('user/pdf', [UserController::class, 'pdf'])->name('userPdf');

    // CRUD Obat
    Route::get('obat/create', [ObatController::class, 'create'])->name('obatCreate');
    Route::post('obat/store', [ObatController::class, 'store'])->name('obatStore');
    Route::get('obat/edit/{obat}', [ObatController::class, 'edit'])->name('obatEdit');
    Route::post('obat/update/{obat}', [ObatController::class, 'update'])->name('obatUpdate');
    Route::delete('obat/destroy/{obat}', [ObatController::class, 'destroy'])->name('obatDestroy');

    // CRUD Supplier
    Route::get('supplier/create', [SupplierController::class, 'create'])->name('supplierCreate');
    Route::post('supplier/store', [SupplierController::class, 'store'])->name('supplierStore');
    Route::get('supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplierEdit');
    Route::post('supplier/update/{id}', [SupplierController::class, 'update'])->name('supplierUpdate');
    Route::delete('supplier/destroy/{id}', [SupplierController::class, 'destroy'])->name('supplierDestroy');

    // CRUD Penjualan
    Route::get('penjualan/edit/{id}', [PenjualanController::class, 'edit'])->name('penjualanEdit');
    Route::put('penjualan/update/{id}', [PenjualanController::class, 'update'])->name('penjualanUpdate');
    Route::delete('penjualan/destroy/{id}', [PenjualanController::class, 'destroy'])->name('penjualanDestroy');

    // CRUD Restok
    Route::get('restok/create', [RestokObatController::class, 'create'])->name('restokCreate');
    Route::post('restok/store', [RestokObatController::class, 'store'])->name('restokStore');
    Route::get('restok/edit/{id}', [RestokObatController::class, 'edit'])->name('restokEdit');
    Route::put('restok/update/{id}', [RestokObatController::class, 'update'])->name('restokUpdate');
    Route::delete('restok/destroy/{id}', [RestokObatController::class, 'destroy'])->name('restokDestroy');

});