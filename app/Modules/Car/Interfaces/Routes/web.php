<?php

use App\Modules\Car\Interfaces\Http\Action\CarAction;
use App\Modules\Car\Interfaces\Http\Requests\CarRequests;

Route::get('', function (CarRequests $request) {
    $cars = (new CarAction)($request);
    return view('car::index', compact('cars'));
});
