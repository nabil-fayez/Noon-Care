<?php

use Illuminate\Support\Facades\Route;

Route::get(
    '/',
    function () {
        return view('welcome');
    }
)->name('welcome');

Route::get('/specialties', [SpecialtyController::class, 'publicIndex'])->name('specialties.index');
