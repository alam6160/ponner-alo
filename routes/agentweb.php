<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\AuthController;
use App\Http\Controllers\Agent\MainController;
use App\Http\Controllers\Agent\PayoutController;
use App\Http\Controllers\Agent\ProductController;
use App\Http\Controllers\Agent\AjaxDeleteController;
use App\Http\Controllers\Agent\ManageOrderController;
use App\Http\Controllers\Agent\ProductAddonController;
use App\Http\Controllers\Agent\ManageRetailerController;
use App\Http\Controllers\Agent\ManageDeliveryBoyController;



Route::match(['get', 'post'], 'agent', [AuthController::class, 'index'])->name('agent')->middleware('agent.guest');

Route::prefix('agent')->name('agent.')->middleware('auth.agent')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::get('subscription-log', [MainController::class, 'subscriptionLog'])->name('subscriptionlog');
    

    //Route::post('apply-payment', [MainController::class, 'apply_payment'])->name('apply-payment');

    Route::prefix('upgrade')->name('upgrade.')->group(function () {
        Route::post('/', [MainController::class, 'upgradeAccount'])->name('index');
        Route::get('page', [MainController::class, 'upgradePage'])->name('page');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::match(['get', 'post'], '/', [MainController::class, 'profile'])->name('index');
        Route::match(['get', 'post'], 'chage-password', [MainController::class, 'chagepassword'])->name('chagepassword');
        Route::match(['get', 'post'], 'bank', [MainController::class, 'updateBank'])->name('bank')->middleware('validatevendorroute');
    });

    Route::prefix('payout')->name('payout.')->middleware('validatevendorroute')->group(function () {
        Route::get('/', [PayoutController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'request', [PayoutController::class, 'request'])->name('request');
        Route::get('creadit-log', [PayoutController::class, 'creaditLog'])->name('creaditlog');
    });

    Route::prefix('product')->name('product.')->middleware('validatevendorroute')->group(function () {
        

        Route::prefix('addon')->name('addon.')->group(function () {
            Route::get('/', [ProductAddonController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ProductAddonController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ProductAddonController::class, 'edit'])->name('edit')->whereNumber('id');
        });

        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/type/{type}', [ProductController::class, 'list_product']);
        Route::match(['get', 'post'], 'view/{id}', [ProductController::class, 'view']);
        Route::get('/delete/{id}', [ProductController::class, 'tr_delete'])->withoutMiddleware([VerifyCsrfToken::class]);
        
        Route::match(['get', 'post'], 'create', [ProductController::class, 'create'])->name('create');
        Route::match(['get', 'post'], 'edit/{id}', [ProductController::class, 'edit'])->name('edit')->whereNumber('id');
        Route::post('delete-image', [ProductController::class, 'delete_image'])->name('deleteimage');
        Route::post('get-image', [ProductController::class, 'get_image'])->name('getimage');
    });

    Route::prefix('manage-order')->name('manageorder.')->middleware('validatevendorroute')->group(function () {
        Route::get('recieved', [ManageOrderController::class, 'recieved'])->name('recieved');
        Route::get('processed', [ManageOrderController::class, 'processed'])->name('processed');
        Route::get('shipped', [ManageOrderController::class, 'shipped'])->name('shipped');
        Route::get('delivered', [ManageOrderController::class, 'delivered'])->name('delivered');
        Route::get('cancel', [ManageOrderController::class, 'cancel'])->name('cancel');
        Route::get('return', [ManageOrderController::class, 'return'])->name('return');
    });

    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::prefix('changestatus')->name('changestatus.')->group(function () {
           
            Route::post('product', [AjaxDeleteController::class, 'product_status'])->name('product');
            Route::post('product-addon', [AjaxDeleteController::class, 'productAddon_status'])->name('productaddon');
        });
    });


    /*
    Route::prefix('manage-user')->name('manage-user.')->group(function () {

        Route::prefix('retailer')->name('retailer.')->group(function () {
            Route::get('/', [ManageRetailerController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageRetailerController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageRetailerController::class, 'edit'])->name('edit')->whereNumber('id');
        });

        Route::prefix('deliveryboy')->name('deliveryboy.')->group(function () {
            Route::get('/', [ManageDeliveryBoyController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageDeliveryBoyController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageDeliveryBoyController::class, 'edit'])->name('edit')->whereNumber('id');
        });
    });
    */
});