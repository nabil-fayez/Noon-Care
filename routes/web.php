<?php
use Illuminate\Support\Facades\Route;

Route::domain('nooncare.test')->group(function () {
Route::get('/',function(){
    return view('welcome');
}
);
});

Route::domain('admin.nooncare.test')->group(function () {
require __DIR__.'/admin.php';
});
require __DIR__.'/doctor.php';
require __DIR__.'/patient.php';
require __DIR__.'/facility.php';