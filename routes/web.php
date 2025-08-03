<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\SponsorController;

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
Route::get('/cars', [CarController::class, 'index'])->name('cars');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');

// Voting API routes
Route::prefix('voting')->name('voting.')->group(function () {
    Route::post('/verify-code', [VotingController::class, 'verifyCode'])->name('verify-code');
    Route::post('/vote', [VotingController::class, 'vote'])->name('vote');
    Route::get('/stats', [VotingController::class, 'getStats'])->name('stats');
});

// Sponsors route
Route::get('/sponsors-page', function() { 
    $sponsors = App\Models\Sponsor::active()->ordered()->get()->groupBy('category');
    $categories = [
        'platinum' => $sponsors->get('platinum', collect()),
        'gold' => $sponsors->get('gold', collect()),
        'silver' => $sponsors->get('silver', collect()),
        'bronze' => $sponsors->get('bronze', collect()),
    ];
    return view('sponsors', compact('categories'));
})->name('sponsors');
