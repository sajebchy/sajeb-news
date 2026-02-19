<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\AdController;

Route::middleware('api')->group(function () {
    // Advertisement APIs (Public)
    Route::prefix('ads')->name('ads.')->group(function () {
        Route::get('/placement/{placement}', [AdController::class, 'getByPlacement'])->name('by-placement');
        Route::get('/random/{placement}', [AdController::class, 'getRandomForPlacement'])->name('random');
        Route::post('/{advertisement}/click', [AdController::class, 'recordClick'])->name('click');
        Route::post('/{advertisement}/impression', [AdController::class, 'recordImpression'])->name('impression');
        Route::get('/{advertisement}/statistics', [AdController::class, 'getStatistics'])->name('statistics');
        Route::get('/trending', [AdController::class, 'getTrending'])->name('trending');
    });
});
