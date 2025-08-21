<?php
use Illuminate\Support\Facades\Route;

Route::domain('nooncare.test')->group(function () {
require __DIR__.'/public.php';
});

Route::domain('admin.nooncare.test')->group(function () {
require __DIR__.'/admin.php';
});

Route::domain('doctor.nooncare.test')->group(function () {
require __DIR__.'/doctor.php';
});

Route::domain('patient.nooncare.test')->group(function () {
require __DIR__.'/patient.php';
});

Route::domain('facility.nooncare.test')->group(function () {
require __DIR__.'/facility.php';
});
