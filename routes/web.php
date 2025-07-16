<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pamphlet\SinglePagePamphlet;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page for all users
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('khodnevis.index');
    }
    return view('welcome');
})->name('welcome');

// Main Khodnevis application
Route::get('/khodnevis', SinglePagePamphlet::class)
    ->middleware('auth')
    ->name('khodnevis.index');

// Dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';