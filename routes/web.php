<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\EnquiryController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Portal\LoginController;
use App\Http\Controllers\Portal\InviteController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\EnquiryController as PortalEnquiryController;
use App\Http\Controllers\Portal\ResponseController;
use App\Http\Controllers\Portal\Admin\UserController;
use App\Http\Controllers\Portal\Admin\ReportsController;
use Illuminate\Support\Facades\Artisan;

Route::get('refresh-system', function () {
    Artisan::call('migrate:fresh --seed');
    exit("System refreshed");
});

Route::get('optimize/clear', function () {
    Artisan::call('optimize:clear');
    exit("Cache, compiled, views, config, route, events are cleared");
});

// ─── PUBLIC SITE ─────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::prefix('legal-resources')->name('faq.')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
    Route::get('/category/{category:slug}', [FaqController::class, 'category'])->name('category');
    Route::get('/category/{category:slug}/{article:slug}', [FaqController::class, 'show'])->name('show');
    Route::post('/article/{article}/feedback', [FaqController::class, 'feedback'])->name('feedback');
});

Route::prefix('get-legal-help')->name('enquiry.')->group(function () {
    Route::get('/', [EnquiryController::class, 'create'])->name('create');
    Route::post('/', [EnquiryController::class, 'store'])->name('store')
         ->middleware('throttle:5,10');
    Route::get('/confirmation', [EnquiryController::class, 'confirmation'])->name('confirmation');
    Route::get('/track', [EnquiryController::class, 'track'])->name('track');
    Route::post('/track', [EnquiryController::class, 'lookup'])->name('lookup')
         ->middleware('throttle:20,5');
});

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{event:slug}', [EventController::class, 'show'])->name('show');
    Route::post('/{event}/register', [EventController::class, 'register'])->name('register')
         ->middleware('throttle:3,5');
});

Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/', [ContactController::class, 'store'])->name('store')
         ->middleware('throttle:3,10');
});

// ─── PORTAL AUTH ──────────────────────────────────────────────────────────────
Route::prefix('portal')->name('portal.')->group(function () {

    // Guest-only (redirect to dashboard if already logged in)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
        Route::get('/invite/{token}', [InviteController::class, 'show'])->name('invite.show');
        Route::post('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ─── Protected portal routes ───────────────────────────────────────────
    Route::middleware('portal.auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Enquiry management
        Route::prefix('enquiries')->name('enquiries.')->group(function () {
            Route::get('/', [PortalEnquiryController::class, 'index'])->name('index');
            Route::get('/{enquiry}', [PortalEnquiryController::class, 'show'])->name('show');
            Route::post('/{enquiry}/assign', [PortalEnquiryController::class, 'assign'])->name('assign');
            Route::post('/{enquiry}/status', [PortalEnquiryController::class, 'updateStatus'])->name('status');
            Route::post('/{enquiry}/note', [PortalEnquiryController::class, 'addNote'])->name('note');
        });

        // Response workflow
        Route::prefix('enquiries')->name('responses.')->group(function () {
            Route::post('/{enquiry}/response/draft', [ResponseController::class, 'saveDraft'])->name('draft');
            Route::post('/{enquiry}/response/submit', [ResponseController::class, 'submit'])->name('submit');
            Route::post('/{enquiry}/response/approve', [ResponseController::class, 'approve'])->name('approve')
                 ->middleware('role:admin,supervisor');
            Route::post('/{enquiry}/response/reject', [ResponseController::class, 'reject'])->name('reject')
                 ->middleware('role:admin,supervisor');
        });

        // ─── Admin-only routes ───────────────────────────────────────────
        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('/{user}', [UserController::class, 'show'])->name('show');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
                Route::post('/{user}/toggle', [UserController::class, 'toggleActive'])->name('toggle');
                Route::post('/{user}/resend-invite', [UserController::class, 'resendInvite'])->name('resend-invite');
            });

            Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
        });

    });
});
