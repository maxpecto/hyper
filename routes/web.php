<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\EventController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/register', [RegistrationController::class, 'show'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/voting', [VotingController::class, 'show'])->name('voting');
Route::get('/events', [EventController::class, 'index'])->name('events');

// Voting API routes
Route::prefix('voting')->name('voting.')->group(function () {
    Route::post('/verify-code', [VotingController::class, 'verifyCode'])->name('verify-code');
    Route::post('/vote', [VotingController::class, 'vote'])->name('vote');
    Route::get('/stats', [VotingController::class, 'getStats'])->name('stats');
});

// Placeholder routes for other pages
Route::get('/cars', function () {
    return view('cars');
})->name('cars');

Route::get('/sponsors', function () {
    return view('sponsors');
})->name('sponsors');
