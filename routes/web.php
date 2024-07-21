<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\rolesController;
use App\Http\Controllers\Public\usersController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', rolesController::class);
    Route::resource('users', usersController::class);
});
