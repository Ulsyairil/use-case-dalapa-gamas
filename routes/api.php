<?php

use App\Http\Controllers\Api\AccessRightController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TroubleTicketController;
use App\Http\Controllers\Api\WorkorderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Inside Auth
Route::middleware('custom.auth.api')->group(function () {

    Route::post('/logout', [AdminController::class, 'logout'])->name('api.logout');

    Route::prefix('ticket')->group(function () {
        Route::controller(TroubleTicketController::class)->group(function () {
            Route::get('/', 'listTicket')->name('api.ticket.list');
            Route::get('/detail', 'ticketDetail')->name('api.ticket.detail');
        });

        Route::controller(WorkorderController::class)->group(function () {
            Route::get('/simulate', 'simulateTicketOrder')->name('api.ticket.simulate');
            Route::get('/verification', 'verification')->name('api.ticket.verification');
        });
    });
    
});

// Outside Auth
Route::controller(AdminController::class)->group(function () {
    Route::post('/login', 'login')->name('api.login');
});