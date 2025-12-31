<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\authentications\Login;
use App\Http\Controllers\Admin\authentications\Register;
use App\Http\Controllers\Admin\authentications\Dashboard;
use App\Http\Controllers\Admin\SocietyController;


// Main Page Route
Route::get('/', [Login::class, 'index'])->name('login');
Route::post('/authenticate', [Login::class, 'authenticate'])->name('login.submit');
Route::get('/register', [Register::class, 'index'])->name('register');
Route::post('/register', [Register::class, 'register'])->name('register.submit');

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/logout', [Login::class, 'logout'])->name('logout');
  Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
  Route::get('/societies', [SocietyController::class, 'index'])->name('societies');
});
