<?php

use App\Modules\Car\Interfaces\Http\Action\ListCarAction;
use App\Modules\Car\Interfaces\Http\Action\ShowCarAction;
use App\Modules\Car\Interfaces\Http\Requests\ShowCarRequests;

Route::get('', ListCarAction::class)->name('car.index');

Route::get('/{id}', ShowCarAction::class)->name('car.show');
