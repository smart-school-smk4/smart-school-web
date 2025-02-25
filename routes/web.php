<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('login' ,[LoginController::class, 'index'])->name('login');
Route::post('login',[LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/siswa', [SiswaController::class, 'index'])->name('admin.siswa');
    Route::get('/guru', [GuruController::class, 'index'])->name('admin.guru');
    Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas');
    Route::get('/jurusan', [JurusanController::class, 'index'])->name('admin.jurusan');
    Route::get('/presensi/siswa', [PresensiController::class, 'indexSiswa'])->name('admin.presensi.siswa');
    Route::get('/presensi/guru', [PresensiController::class, 'indexGuru'])->name('admin.presensi.guru');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
});