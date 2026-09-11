<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    //Route::inertia('/dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
