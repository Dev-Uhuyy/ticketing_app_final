<?php

use App\Http\Controllers\SuperAdmin\CategoryController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\HistoriesController;
use App\Http\Controllers\SuperAdmin\PaymentMethodController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\VoucherController;

use App\Http\Controllers\PengelolaEvent\DashboardController as PengelolaDashboard;
use App\Http\Controllers\PengelolaEvent\EventController as PengelolaEventController;
use App\Http\Controllers\PengelolaEvent\TiketController;
use App\Http\Controllers\PengelolaEvent\TicketTypeController;

use App\Http\Controllers\Pembeli\EventController as PembeliEventController;
use App\Http\Controllers\Pembeli\HomeController;
use App\Http\Controllers\Pembeli\OrderController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Events
Route::get('/events/{event}', [PembeliEventController::class, 'show'])->name('events.show');

// Orders
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/voucher/validate', [OrderController::class, 'validateVoucher'])->name('voucher.validate');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::middleware('superadmin')->prefix('admin')->name('superadmin.')->group(function () {
        Route::get('/', [SuperAdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);

        Route::resource('vouchers', VoucherController::class);
        Route::post('vouchers/{id}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggleStatus');
        Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
        Route::post('payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
        Route::put('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
        Route::delete('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

        Route::get('/histories', [HistoriesController::class, 'index'])->name('histories.index');
        Route::get('/histories/{id}', [HistoriesController::class, 'show'])->name('histories.show');
    });

    Route::middleware('pengelola')->prefix('pengelola')->name('pengelola.')->group(function () {
        Route::get('/', [PengelolaDashboard::class, 'index'])->name('dashboard');

        Route::resource('events', PengelolaEventController::class);

        Route::resource('tickets', TiketController::class);

        Route::resource('ticket-types', TicketTypeController::class);
    });
});

require __DIR__ . '/auth.php';
