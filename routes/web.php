<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Donor\RegisterController;
use App\Http\Controllers\Donor\LoginController;
use App\Http\Controllers\Donor\DashboardController;
use App\Http\Controllers\Donor\ProfileController;
use App\Http\Controllers\Donor\BloodRequestController as DonorBloodRequestController;
use App\Http\Controllers\Admin\LoginController    as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DonorController    as AdminDonorController;
use App\Http\Controllers\Admin\HospitalController as AdminHospitalController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\AiPredictionController as AdminAiPredictionController;
use App\Http\Controllers\Donor\NotificationController as DonorNotificationController;
use App\Http\Controllers\Hospital\NotificationController as HospitalNotificationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\BloodInventoryController as AdminBloodInventoryController;
use App\Http\Controllers\Donor\AppointmentController as DonorAppointmentController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\LanguageController;
use App\Models\Donor;
use App\Models\Hospital;
use App\Services\AiEligibilityService;

// ─── LANGUAGE ─────────────────────────────────────────────
Route::post('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// ─── LANDING PAGE ─────────────────────────────────────────
Route::get('/', function () {
    $modelMetrics  = (new AiEligibilityService())->getModelInfo();
    $donorCount    = Donor::count();
    $hospitalCount = Hospital::count();
    return view('donor.blood_donor_landing_page', compact('modelMetrics', 'donorCount', 'hospitalCount'));
})->name('home');

// ─── DONOR AUTH ───────────────────────────────────────────
Route::prefix('donor')->name('donor.')->group(function () {

    // Guest only (redirect to dashboard if already logged in)
    Route::middleware('guest:donor')->group(function () {
        Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:register');

        Route::get('/login', [LoginController::class, 'showForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
    });

    // Protected (must be logged in)
    Route::middleware('donor.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/requests', [DonorBloodRequestController::class, 'index'])->name('requests.index');
        Route::post('/requests/{bloodRequest}/respond', [DonorBloodRequestController::class, 'respond'])->name('requests.respond');

        Route::get('/notifications', [DonorNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [DonorNotificationController::class, 'markAllRead'])->name('notifications.readAll');
        Route::post('/notifications/{notification}/read', [DonorNotificationController::class, 'markRead'])->name('notifications.read');

        // Appointments
        Route::get('/appointments', [DonorAppointmentController::class, 'index'])->name('appointments.index');
        Route::post('/appointments/{bloodRequest}', [DonorAppointmentController::class, 'store'])->name('appointments.store');
        Route::post('/appointments/{appointment}/cancel', [DonorAppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

});




// ─── ADMIN ────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest only
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AdminLoginController::class, 'showForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->middleware('throttle:login');
    });

    // Protected
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout',   [AdminLoginController::class, 'logout'])->name('logout');

        // Donor management
        Route::get('/donors',                    [AdminDonorController::class, 'index'])->name('donors.index');
        Route::get('/donors/{donor}',            [AdminDonorController::class, 'show'])->name('donors.show');
        Route::post('/donors/{donor}/toggle',    [AdminDonorController::class, 'toggleEligibility'])->name('donors.toggle');
        Route::post('/donors/{donor}/donations', [AdminDonorController::class, 'storeDonation'])->name('donors.donations.store');
        Route::delete('/donors/{donor}',         [AdminDonorController::class, 'destroy'])->name('donors.destroy');

        // Hospital management
        Route::get('/hospitals',                       [AdminHospitalController::class, 'index'])->name('hospitals.index');
        Route::get('/hospitals/{hospital}',             [AdminHospitalController::class, 'show'])->name('hospitals.show');
        Route::post('/hospitals/{hospital}/toggle',     [AdminHospitalController::class, 'toggleVerification'])->name('hospitals.toggle');
        Route::delete('/hospitals/{hospital}',          [AdminHospitalController::class, 'destroy'])->name('hospitals.destroy');

        // AI prediction audit log
        Route::get('/ai-predictions', [AdminAiPredictionController::class, 'index'])->name('ai-predictions.index');

        // Blood inventory (read-only, cross-hospital)
        Route::get('/inventory', [AdminBloodInventoryController::class, 'index'])->name('inventory.index');

        // Appointments (read-only, cross-hospital)
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/{type}/{format}', [AdminReportController::class, 'export'])->name('reports.export');

        // Activity log
        Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');

        // Notifications
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('notifications.readAll');
        Route::post('/notifications/{notification}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');

        // Admin profile / settings
        Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    });

});


Route::get('/about', function () {
    $modelMetrics    = (new AiEligibilityService())->getModelInfo();
    $hospitalCount   = Hospital::count();
    return view('donor/about', compact('modelMetrics', 'hospitalCount'));
})->name('about');

Route::get('/contact', function () {
    return view('donor/contact');
})->name('contact');

Route::get('/find-donors', function () {
    $query = Donor::query()->where('is_eligible', true);

    if (request()->filled('blood_group')) {
        $query->where('blood_group', request('blood_group'));
    }

    if (request()->filled('district')) {
        $query->where('district', request('district'));
    }

    if (request()->filled('min_donations')) {
        $query->where('total_donations', '>=', (int) request('min_donations'));
    }

    $donors = $query->orderByDesc('ai_confidence')->paginate(9);
    $modelMetrics = (new AiEligibilityService())->getModelInfo();

    return view('donor/find_donor', compact('donors', 'modelMetrics'));
})->name('find-donors');

Route::get('/privacy', function () {
    return view('donor/privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('donor/terms');
})->name('terms');


use App\Http\Controllers\Hospital\LoginController      as HospitalLoginController;
use App\Http\Controllers\Hospital\RegisterController   as HospitalRegisterController;
use App\Http\Controllers\Hospital\DashboardController  as HospitalDashboardController;
use App\Http\Controllers\Hospital\ProfileController    as HospitalProfileController;
use App\Http\Controllers\Hospital\BloodRequestController;
use App\Http\Controllers\Hospital\BloodInventoryController as HospitalBloodInventoryController;
use App\Http\Controllers\Hospital\AppointmentController as HospitalAppointmentController;
use App\Http\Controllers\Hospital\ReportController as HospitalReportController;
use App\Http\Controllers\ChatController;


Route::post('/chat', [ChatController::class, 'chat'])->name('chat')->middleware('throttle:chat');

// ─── HOSPITAL ─────────────────────────────────────────────
Route::prefix('hospital')->name('hospital.')->group(function () {

    // Guest only
    Route::middleware('guest:hospital')->group(function () {
        Route::get('/register', [HospitalRegisterController::class, 'showForm'])->name('register');
        Route::post('/register', [HospitalRegisterController::class, 'register'])->middleware('throttle:register');

        Route::get('/login',  [HospitalLoginController::class, 'showForm'])->name('login');
        Route::post('/login', [HospitalLoginController::class, 'login'])->middleware('throttle:login');
    });

    // Protected
    Route::middleware('hospital.auth')->group(function () {
        Route::get('/dashboard', [HospitalDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout',   [HospitalLoginController::class, 'logout'])->name('logout');

        Route::get('/profile/edit', [HospitalProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [HospitalProfileController::class, 'update'])->name('profile.update');

        // Blood requests
        Route::get('/request',               [BloodRequestController::class, 'create'])->name('request.create');
        Route::post('/request',              [BloodRequestController::class, 'store'])->name('request.store')->middleware('throttle:blood-request');
        Route::get('/requests',              [BloodRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/{bloodRequest}', [BloodRequestController::class, 'show'])->name('requests.show');
        Route::post('/requests/{bloodRequest}/fulfill', [BloodRequestController::class, 'fulfill'])->name('requests.fulfill');
        Route::post('/requests/{bloodRequest}/notify/{donor}', [BloodRequestController::class, 'notifyDonor'])->name('requests.notify')->middleware('throttle:donor-notify');

        // Blood inventory
        Route::get('/inventory',         [HospitalBloodInventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/history', [HospitalBloodInventoryController::class, 'history'])->name('inventory.history');
        Route::post('/inventory/{inventory}/add',       [HospitalBloodInventoryController::class, 'addUnits'])->name('inventory.add');
        Route::post('/inventory/{inventory}/remove',    [HospitalBloodInventoryController::class, 'removeUnits'])->name('inventory.remove');
        Route::post('/inventory/{inventory}/threshold', [HospitalBloodInventoryController::class, 'updateThreshold'])->name('inventory.threshold');

        Route::get('/notifications', [HospitalNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [HospitalNotificationController::class, 'markAllRead'])->name('notifications.readAll');
        Route::post('/notifications/{notification}/read', [HospitalNotificationController::class, 'markRead'])->name('notifications.read');

        // Appointments
        Route::get('/appointments', [HospitalAppointmentController::class, 'index'])->name('appointments.index');
        Route::post('/appointments/{appointment}/approve', [HospitalAppointmentController::class, 'approve'])->name('appointments.approve');
        Route::post('/appointments/{appointment}/reject', [HospitalAppointmentController::class, 'reject'])->name('appointments.reject');
        Route::post('/appointments/{appointment}/reschedule', [HospitalAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        Route::post('/appointments/{appointment}/complete', [HospitalAppointmentController::class, 'complete'])->name('appointments.complete');

        // Reports
        Route::get('/reports', [HospitalReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/{type}/{format}', [HospitalReportController::class, 'export'])->name('reports.export');
    });

});


// Admin-only connectivity check for the Anthropic API -- was previously a
// fully public, unauthenticated route that also echoed a fragment of the
// live API key in its JSON response. Restricted to admins and no longer
// discloses any part of the key; the key's mere presence/validity is
// conveyed by the `status` field alone.
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
    ]);
})->middleware(['admin.auth', 'throttle:chat'])->name('test-claude');