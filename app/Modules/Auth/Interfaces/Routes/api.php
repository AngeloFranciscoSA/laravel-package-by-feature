<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Interfaces\Http\Action\LoginAuthAction;
use App\Modules\Auth\Interfaces\Http\Action\RegisterAuthAction;
use App\Modules\Auth\Interfaces\Http\Action\LogoutAuthAction;

Route::post('auth/login', LoginAuthAction::class)->name('api.auth.login');
Route::post('auth/register', RegisterAuthAction::class)->name('api.auth.register');
Route::post('auth/logout', LogoutAuthAction::class)->middleware('auth:sanctum')->name('api.auth.logout');