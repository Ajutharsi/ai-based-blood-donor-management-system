<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Donor\RegisterController;
use App\Http\Controllers\Donor\LoginController;
use App\Http\Controllers\Donor\DashboardController;
use App\Http\Controllers\Admin\LoginController    as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DonorController    as AdminDonorController;

// ─── LANDING PAGE ─────────────────────────────────────────
Route::get('/', function () {
    return view('donor.blood_donor_landing_page');
});

// ─── DONOR AUTH ───────────────────────────────────────────
Route::prefix('donor')->name('donor.')->group(function () {

    // Guest only (redirect to dashboard if already logged in)
    Route::middleware('guest:donor')->group(function () {
        Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);

        Route::get('/login', [LoginController::class, 'showForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    // Protected (must be logged in)
    Route::middleware('donor.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });

});




// ─── ADMIN ────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest only
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AdminLoginController::class, 'showForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });

    // Protected
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout',   [AdminLoginController::class, 'logout'])->name('logout');

        // Donor management
        Route::get('/donors',                    [AdminDonorController::class, 'index'])->name('donors.index');
        Route::get('/donors/{donor}',            [AdminDonorController::class, 'show'])->name('donors.show');
        Route::post('/donors/{donor}/toggle',    [AdminDonorController::class, 'toggleEligibility'])->name('donors.toggle');
        Route::delete('/donors/{donor}',         [AdminDonorController::class, 'destroy'])->name('donors.destroy');
    });

});


Route::get('/about', function () {
    return view('donor/about');
})->name('about');

Route::get('/contact', function () {
    return view('donor/contact');
})->name('contact');

Route::get('/Find', function () {
    return view('donor/find_donor');
})->name('Find');

Route::get('/privacy', function () {
    return view('donor/privacy');
})->name('privacy');


use App\Http\Controllers\Hospital\LoginController      as HospitalLoginController;
use App\Http\Controllers\Hospital\DashboardController  as HospitalDashboardController;
use App\Http\Controllers\Hospital\BloodRequestController;
use App\Http\Controllers\ChatController;


Route::post('/chat', [ChatController::class, 'chat'])->name('chat');

// ─── HOSPITAL ─────────────────────────────────────────────
Route::prefix('hospital')->name('hospital.')->group(function () {

    // Guest only
    Route::middleware('guest:hospital')->group(function () {
        Route::get('/login',  [HospitalLoginController::class, 'showForm'])->name('login');
        Route::post('/login', [HospitalLoginController::class, 'login']);
    });

    // Protected
    Route::middleware('hospital.auth')->group(function () {
        Route::get('/dashboard', [HospitalDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout',   [HospitalLoginController::class, 'logout'])->name('logout');

        // Blood requests
        Route::get('/request',               [BloodRequestController::class, 'create'])->name('request.create');
        Route::post('/request',              [BloodRequestController::class, 'store'])->name('request.store');
        Route::get('/requests',              [BloodRequestController::class, 'index'])->name('requests.index');
        Route::post('/requests/{bloodRequest}/fulfill', [BloodRequestController::class, 'fulfill'])->name('requests.fulfill');
    });

});


Route::get('/test-claude', function () {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'x-api-key'         => env('ANTHROPIC_API_KEY'),
        'anthropic-version' => '2023-06-01',
        'content-type'      => 'application/json',
    ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
        'model'      => 'claude-haiku-4-5-20251001',
        'max_tokens' => 100,
        'messages'   => [
            ['role' => 'user', 'content' => 'Say hello'],
        ],
    ]);

    return response()->json([
        'status'   => $response->status(),
        'response' => $response->json(),
        'api_key'  => substr(env('ANTHROPIC_API_KEY'), 0, 15) . '...',
    ]);
});