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
use App\Http\Controllers\Portal\Admin\HomeContentController;
use App\Http\Controllers\Portal\Admin\FaqAdminController;
use App\Http\Controllers\Portal\Admin\EventAdminController;

use Illuminate\Support\Facades\Artisan;

Route::get('/storage-link', function () {
    Artisan::call('storage:link');

    return Artisan::output();
});

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

            // ── Legal Resources (FAQ) management ──────────────────────────
            Route::prefix('faq')->name('faq.')->group(function () {
                Route::get('/', [FaqAdminController::class, 'index'])->name('index');
                Route::get('/categories/create', [FaqAdminController::class, 'createCategory'])->name('categories.create');
                Route::post('/categories', [FaqAdminController::class, 'storeCategory'])->name('categories.store');
                Route::get('/categories/{category}/edit', [FaqAdminController::class, 'editCategory'])->name('categories.edit');
                Route::put('/categories/{category}', [FaqAdminController::class, 'updateCategory'])->name('categories.update');
                Route::delete('/categories/{category}', [FaqAdminController::class, 'destroyCategory'])->name('categories.destroy');

                Route::get('/categories/{category}/articles', [FaqAdminController::class, 'articlesIndex'])->name('articles.index');
                Route::get('/categories/{category}/articles/create', [FaqAdminController::class, 'createArticle'])->name('articles.create');
                Route::post('/categories/{category}/articles', [FaqAdminController::class, 'storeArticle'])->name('articles.store');
                Route::get('/categories/{category}/articles/{article}/edit', [FaqAdminController::class, 'editArticle'])->name('articles.edit');
                Route::put('/categories/{category}/articles/{article}', [FaqAdminController::class, 'updateArticle'])->name('articles.update');
                Route::delete('/categories/{category}/articles/{article}', [FaqAdminController::class, 'destroyArticle'])->name('articles.destroy');
            });

            // ── Events management ──────────────────────────────────────────
            Route::prefix('events')->name('events.')->group(function () {
                Route::get('/', [EventAdminController::class, 'index'])->name('index');
                Route::get('/create', [EventAdminController::class, 'create'])->name('create');
                Route::post('/', [EventAdminController::class, 'store'])->name('store');
                Route::get('/{event}/edit', [EventAdminController::class, 'edit'])->name('edit');
                Route::put('/{event}', [EventAdminController::class, 'update'])->name('update');
                Route::delete('/{event}', [EventAdminController::class, 'destroy'])->name('destroy');
                Route::post('/{event}/toggle', [EventAdminController::class, 'togglePublish'])->name('toggle');
            });

            // ── Homepage content management ───────────────────────────────
            Route::prefix('content')->name('content.')->group(function () {
                Route::get('/', [HomeContentController::class, 'index'])->name('index');

                Route::post('/hero', [HomeContentController::class, 'heroStore'])->name('hero.store');
                Route::put('/hero/{slide}', [HomeContentController::class, 'heroUpdate'])->name('hero.update');
                Route::delete('/hero/{slide}', [HomeContentController::class, 'heroDestroy'])->name('hero.destroy');
                Route::post('/hero/reorder', [HomeContentController::class, 'heroReorder'])->name('hero.reorder');

                Route::post('/gallery', [HomeContentController::class, 'galleryStore'])->name('gallery.store');
                Route::put('/gallery/{item}', [HomeContentController::class, 'galleryUpdate'])->name('gallery.update');
                Route::delete('/gallery/{item}', [HomeContentController::class, 'galleryDestroy'])->name('gallery.destroy');
                Route::post('/gallery/reorder', [HomeContentController::class, 'galleryReorder'])->name('gallery.reorder');

                Route::post('/steps', [HomeContentController::class, 'stepStore'])->name('steps.store');
                Route::put('/steps/{step}', [HomeContentController::class, 'stepUpdate'])->name('steps.update');
                Route::delete('/steps/{step}', [HomeContentController::class, 'stepDestroy'])->name('steps.destroy');
                Route::post('/steps/reorder', [HomeContentController::class, 'stepReorder'])->name('steps.reorder');

                Route::post('/marquee', [HomeContentController::class, 'marqueeStore'])->name('marquee.store');
                Route::put('/marquee/{item}', [HomeContentController::class, 'marqueeUpdate'])->name('marquee.update');
                Route::delete('/marquee/{item}', [HomeContentController::class, 'marqueeDestroy'])->name('marquee.destroy');
                Route::post('/marquee/reorder', [HomeContentController::class, 'marqueeReorder'])->name('marquee.reorder');

                Route::put('/stats', [HomeContentController::class, 'statsUpdate'])->name('stats.update');
            });
        });

    });
});
