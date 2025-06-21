<?php

use App\Modules\Car\Interfaces\Http\Action\ListCarAction;
use App\Modules\Car\Interfaces\Http\Action\ShowCarAction;
use App\Modules\Car\Interfaces\Http\Requests\ListCarRequests;
use App\Modules\Car\Interfaces\Http\Requests\ShowCarRequests;

Route::get('', function (ListCarRequests $request) {
    $cars = (new ListCarAction)($request);
    return view('car::index', compact('cars'));
});

Route::get('/{id}', function (ShowCarRequests $request) {
    $car = (new ShowCarAction)($request);
    return view('car::show', compact('car'));
});
