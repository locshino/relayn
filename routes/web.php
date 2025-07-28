<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download/mass-order-template', [FileController::class, 'downloadMassOrderTemplate'])
    ->name('download.mass-order-template')
    ->middleware('auth'); // Ensure the user is authenticated before downloading the file
