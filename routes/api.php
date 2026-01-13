<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\AuthController;

Route::get('/cities/{state}', [CommonController::class, 'cities'])->name('cities');

Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::get('/profile', [AuthController::class, 'profile']);
  Route::post('/update-profile', [AuthController::class, 'profile_update']);
  Route::post('/change-password', [AuthController::class, 'change_password']);
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
