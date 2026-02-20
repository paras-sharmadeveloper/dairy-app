<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ShopController, ProductController, PublicOrderController, OrderController};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::middleware(['auth'])->group(function () {

    Route::resource('products', ProductController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('shops', ShopController::class);
});

Route::get(
    '/order/{token}',
    [PublicOrderController::class, 'show']
);

Route::post(
    '/order/{token}',
    [PublicOrderController::class, 'submit']
);


Route::middleware(['auth'])->group(function () {

    Route::get(
        'orders/vendor-create',
        [OrderController::class, 'vendorCreate']
    )
        ->name('orders.vendorCreate');

    Route::post(
        'orders/vendor-store',
        [OrderController::class, 'vendorStore']
    )
        ->name('orders.vendorStore');
});


Route::get(
    'orders/{order}/deliver',
    [OrderController::class, 'deliver']
)
    ->name('orders.deliver');

Route::post(
    'orders/{order}/deliver',
    [OrderController::class, 'saveDelivery']
)
    ->name('orders.saveDelivery');


Route::get(
    'orders',
    [OrderController::class, 'index']
)
    ->name('orders.index');
Route::get(
    'orders/{order}/invoice',
    [OrderController::class, 'invoice']
)
    ->name('orders.invoice');


    Route::get('/invoice/{token}',
    [OrderController::class,'publicInvoice'])
    ->name('orders.publicInvoice');
