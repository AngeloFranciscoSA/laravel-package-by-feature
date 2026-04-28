<?php

use App\Modules\Car\Interfaces\Http\Action\ListCarAction;
use App\Modules\Car\Interfaces\Http\Action\ShowCarAction;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('cars')->group(function () {
    Route::get('/', ListCarAction::class);
    Route::get('/{id}', ShowCarAction::class);
});
