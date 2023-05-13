<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Statehead\AuthController;
use App\Http\Controllers\Statehead\MainController;
use App\Http\Controllers\Statehead\ManageAgentController;
use App\Http\Controllers\Statehead\ManageRetailerController;
use App\Http\Controllers\Statehead\ManageDeliveryBoyController;

Route::match(['get', 'post'], 'statehead', [AuthController::class, 'index'])->name('statehead')->middleware('statehead.guest');

Route::prefix('statehead')->name('statehead.')->middleware('auth.statehead')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::post('apply-payment', [MainController::class, 'apply_payment'])->name('apply-payment');

    
    Route::prefix('manage-user')->name('manage-user.')->group(function () {

        Route::prefix('agent')->name('agent.')->group(function () {
            Route::get('/', [ManageAgentController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageAgentController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageAgentController::class, 'edit'])->name('edit')->whereNumber('id');
        });

        Route::prefix('deliveryboy')->name('deliveryboy.')->group(function () {
            Route::get('/', [ManageDeliveryBoyController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageDeliveryBoyController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageDeliveryBoyController::class, 'edit'])->name('edit')->whereNumber('id');
        });
        
        Route::prefix('retailer')->name('retailer.')->group(function () {
            Route::get('/', [ManageRetailerController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageRetailerController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageRetailerController::class, 'edit'])->name('edit')->whereNumber('id');
        });
    });
});