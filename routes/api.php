<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BelController;

Route::prefix('bel')->group(function () {
    Route::post('/ring', [BelController::class, 'ring'])->name('api.bel.ring');
    Route::post('/sync', [BelController::class, 'syncSchedule'])->name('api.bel.sync');
    Route::get('/status', [BelController::class, 'status'])->name('api.bel.status');
    Route::get('/next-schedule', [BelController::class, 'getNextSchedule'])->name('api.bel.next-schedule');
    Route::put('/{id}/toggle-status', [BelController::class, 'toggleStatus'])->name('api.bel.toggle-status');
    Route::post('/activate-all', [BelController::class, 'activateAll'])->name('api.bel.activate-all');
    Route::post('/deactivate-all', [BelController::class, 'deactivateAll'])->name('api.bel.deactivate-all');
});