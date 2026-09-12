<?php

use App\Http\Controllers\BirthdayController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [BirthdayController::class, 'index'])->name('home');
    Route::get('/birthday/create', [BirthdayController::class, 'create'])->name('birthday.create');
    Route::post('/birthday', [BirthdayController::class, 'store'])->name('birthday.store');
    Route::get('/birthday/{birthday}/edit', [BirthdayController::class, 'edit'])->name('birthday.edit');
    Route::put('/birthday/{birthday}', [BirthdayController::class, 'update'])->name('birthday.update');
    Route::delete('/birthday/{birthday}', [BirthdayController::class, 'destroy'])->name('birthday.destroy');

    // Route::inertia('/dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
