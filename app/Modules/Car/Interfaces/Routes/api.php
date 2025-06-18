<?php

// Rotas da API do módulo Car

use App\Modules\Car\Interfaces\Http\Action\CarAction;
use Illuminate\Support\Facades\Route;

Route::prefix('cars')->group(function () {
    Route::get('/', CarAction::class);
});
