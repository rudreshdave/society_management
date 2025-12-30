<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CommonController;

Route::get('/cities/{state}', [CommonController::class, 'cities'])->name('cities');
