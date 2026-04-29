<?php

use App\Modules\Car\Interfaces\Http\Action\HomeCarAction;
use App\Modules\Car\Interfaces\Http\Action\SearchCarAction;
use App\Modules\Car\Interfaces\Http\Action\CompareCarAction;
use App\Modules\Car\Interfaces\Http\Action\StoreCarAction;
use App\Modules\Car\Interfaces\Http\Action\ShowCarAction;
use App\Modules\Car\Interfaces\Http\Action\UpdateCarAction;
use App\Modules\Car\Interfaces\Http\Action\DestroyCarAction;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('', HomeCarAction::class)->name('cars.index');
    Route::get('/search', SearchCarAction::class)->name('cars.search');
    Route::get('/compare', CompareCarAction::class)->name('cars.compare');
    Route::get('/new', fn () => Inertia::render('Car/NewListing'))->name('cars.new');
    Route::post('/new', StoreCarAction::class)->name('cars.store');
    Route::get('/{id}', ShowCarAction::class)->name('cars.show');
    Route::put('/{id}', UpdateCarAction::class)->name('cars.update');
    Route::delete('/{id}', DestroyCarAction::class)->name('cars.destroy');
});