<?php

use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

#Presensi
Route::post('/presensi', [PresensiController::class, 'store']);

Route::prefix('admin')->group(function () {
    Route::apiResource('/siswa', SiswaController::class)->only(['index', 'store']);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API route works!']);
});

Route::prefix('admin')->group(function () {
    Route::get('/ping', fn() => response()->json(['status' => 'admin route works']));
});