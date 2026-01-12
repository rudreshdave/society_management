<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\AuthController;

Route::get('/cities/{state}', [CommonController::class, 'cities'])->name('cities');

Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
