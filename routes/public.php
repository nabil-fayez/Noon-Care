<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpecialtyController;

Route::get(
    '/',
    function () {
        return view('welcome');
    }
)->name('welcome');

Route::get('/specialties', [SpecialtyController::class, 'publicIndex'])->name('specialties.index');