<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingsController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('listings', [ListingsController::class, 'index'])->name('listings.index');
    Route::get('listings/create', [ListingsController::class, 'create'])->name('listings.create');
    Route::post('listings', [ListingsController::class, 'store'])->name('listings.store');
    Route::get('listings/{listing}/edit', [ListingsController::class, 'edit'])->name('listings.edit');
    Route::delete('listings/{listing}', [ListingsController::class, 'destroy'])->name('listings.destroy');
    Route::put('listings/{listing}', [ListingsController::class, 'update'])->name('listings.update');
});


require __DIR__.'/auth.php';
