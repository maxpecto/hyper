<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\AdminController;

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
Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegistrationController::class, 'show'])->name('register.show');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

// Voting routes
Route::get('/voting', [VotingController::class, 'show'])->name('voting.show');
Route::post('/voting/verify-code', [VotingController::class, 'verifyCode'])->name('voting.verify-code');
Route::post('/voting/vote', [VotingController::class, 'vote'])->name('voting.vote');
Route::get('/voting/stats', [VotingController::class, 'getStats'])->name('voting.stats');

// Admin routes (Filament handles admin panel)
