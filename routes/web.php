<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\Login;
use App\Http\Controllers\authentications\Dashboard;


// Main Page Route
Route::get('/', [Login::class, 'index'])->name('login');
Route::post('/authenticate', [Login::class, 'authenticate'])->name('login.submit');

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/logout', [Login::class, 'logout'])->name('logout');
  Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
});
