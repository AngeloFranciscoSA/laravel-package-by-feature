<?php

use App\Modules\Car\Interfaces\Http\Action\DestroyCarAction;
use App\Modules\Car\Interfaces\Http\Action\ListCarAction;
use App\Modules\Car\Interfaces\Http\Action\ShowCarAction;
use App\Modules\Car\Interfaces\Http\Action\UpdateCarAction;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('', ListCarAction::class)->name('cars.index');
    Route::get('/{id}', ShowCarAction::class)->name('cars.show');
    Route::put('/{id}', UpdateCarAction::class)->name('cars.update');
    Route::delete('/{id}', DestroyCarAction::class)->name('cars.destroy');
});
