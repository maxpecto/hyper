<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/register', [RegistrationController::class, 'show'])->name('register.show');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

// Voting routes
Route::get('/voting', [VotingController::class, 'show'])->name('voting.show');
Route::post('/voting/verify-code', [VotingController::class, 'verifyCode'])->name('voting.verify-code');
Route::post('/voting/vote', [VotingController::class, 'vote'])->name('voting.vote');
Route::get('/voting/stats', [VotingController::class, 'getStats'])->name('voting.stats');

// Placeholder routes for other pages (will be implemented later)
Route::get('/events', function () {
    return view('events');
})->name('events');

Route::get('/cars', function () {
    return view('cars');
})->name('cars');

Route::get('/sponsors', function () {
    return view('sponsors');
})->name('sponsors');

// Admin routes (Filament handles admin panel)
