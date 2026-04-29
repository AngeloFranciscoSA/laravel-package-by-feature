<?php

use App\Modules\Car\Interfaces\Http\Action\ShowSellerAction;
use Illuminate\Support\Facades\Route;

Route::get('/{id}', ShowSellerAction::class)->name('sellers.show');