<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Interfaces\Http\Action\LoginAuthAction;
use App\Modules\Auth\Interfaces\Http\Action\RegisterAuthAction;
use App\Modules\Auth\Interfaces\Http\Action\LogoutAuthAction;

Route::get('', LoginAuthAction::class)->name('auth.login');
Route::post('login', LoginAuthAction::class)->name('auth.login.submit');
Route::get('register', RegisterAuthAction::class)->name('auth.register');
Route::post('register', RegisterAuthAction::class)->name('auth.register.submit');
Route::post('logout', LogoutAuthAction::class)->middleware('auth')->name('auth.logout');