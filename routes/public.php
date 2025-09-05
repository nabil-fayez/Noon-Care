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

Route::get('/test', function () {
    return "<img src='/storage/patients/profile_images/0pz5NtTzbA3BnrmKqEe9Z1lT66MWdCQCYd3S20t1.jpg'/>";
});


// في routes/web.php أو routes/admin.php
