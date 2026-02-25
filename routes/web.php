<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\Admin\HistoriesController;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\TicketTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Events
Route::get('/events/{event}', [UserEventController::class, 'show'])->name('events.show');

// Orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Reviews
    Route::get('/events/{event}/reviews', [OrderController::class, 'createReview'])->name('reviews.create');
    Route::post('/events/{event}/reviews', [OrderController::class, 'storeReview'])->name('reviews.store');

    Route::middleware('superadmin')->prefix('admin')->name('superadmin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::get('/histories', [HistoriesController::class, 'index'])->name('histories.index');
        Route::get('/histories/{id}', [HistoriesController::class, 'show'])->name('histories.show');
    });

    Route::middleware('pengelola')->prefix('pengelola')->name('pengelola.')->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');


        Route::resource('events', AdminEventController::class);

        Route::resource('tickets', TiketController::class);

        Route::resource('ticket-types', TicketTypeController::class);
    });
});

require __DIR__ . '/auth.php';
