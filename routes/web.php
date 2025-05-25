<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\TroubleTicketController;
use App\Http\Controllers\Web\WorkorderController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Inside Auth
Route::middleware('custom.auth.web')->group(function () {

    // View Route
    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {
        
        Route::prefix('ticket')->group(function () {
            Route::controller(TroubleTicketController::class)->group(function () {
                Route::get('/', 'listTicketView')->name('ticket.view');
                Route::get('/detail', 'detailTicketView')->name('ticket.detail.view');
            });
        });

    });

    // API Front Route
    Route::post('/logout-request', [AdminController::class, 'logout'])->name('logout');

    Route::prefix('ticket')->group(function () {
        
        Route::controller(TroubleTicketController::class)->group(function () {
            Route::post('/list-request', 'listTicket')->name('ticket.list'); 
            Route::post('/detail-request', 'detailTicket')->name('ticket.detail');
        });

        Route::controller(WorkorderController::class)->group(function () {
            Route::post('/simulate-request', 'simulateTicket')->name('ticket.simulate');
            Route::post('/verification-request', 'verification')->name('ticket.verification');
        });

    });

});

// Outside Auth
Route::middleware('custom.auth.web:guest')->group(function () {

    // View Route
    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/login', 'loginView')->name('login.view');
        });
    });

    // API Front Route
    Route::controller(AdminController::class)->group(function () {
        Route::post('/login-request', 'login')->name('login');
    });
});

Route::controller(AdminController::class)->group(function () {
    Route::post('/change-locale', 'changeLocale')->name('change.locale');
});

Route::get('/', function () {
    return redirect()->route('login.view');
});