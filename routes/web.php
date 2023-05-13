<?php

use App\Http\Controllers\Web;
use App\Http\Controllers\AJAX;

use App\Http\Controllers\User\Main;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\Account;
use App\Http\Controllers\User\Wishlist;
use App\Http\Controllers\Review;

use App\Http\Middleware\UserAfterLogin;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\CheckoutController;
use Illuminate\Support\Facades\Artisan;

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

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});
Route::get('/migrate', function () {
   Artisan::call("migrate");
   return 'Application migrated';
});

Route::get('/', [Web::class, 'home'])->name('home');
Route::get('about-us', [Web::class, 'aboutUs'])->name('aboutus');
Route::match(['get', 'post'], 'contact-us', [Web::class, 'contactUs'])->name('contactus');
Route::get('terms-conditions', [Web::class, 'termsConditions'])->name('termsconditions');
Route::get('privacy-policy', [Web::class, 'privacyPolicy'])->name('privacypolicy');

Route::get('product/{slug}', [Web::class, 'product_details']);
Route::get('products-list/{slug}', [Web::class, 'products_list']);
Route::get('review-list/{id}', [Review::class, 'review_list']);

Route::match(['get','post'], 'sign-in', [Main::class, 'signin'])->middleware('user.beforelogin');
Route::match(['get','post'], 'sign-up', [Main::class, 'signup'])->middleware('user.beforelogin');
Route::match(['get','post'], 'reset-password', [Main::class, 'reset_password'])->middleware('user.beforelogin');
Route::get('sign-out', [Main::class, 'signout']);

Route::prefix('user')->name('user.')->middleware('user.afterlogin')->group(function()
{
    Route::prefix('account')->withoutMiddleware([VerifyCsrfToken::class])->group(function()
    {
        Route::post('update-name', [Account::class, 'update_name']);
        Route::post('update-email', [Account::class, 'update_email']);
        Route::post('update-phone', [Account::class, 'update_phone']);
        Route::post('update-raddress', [Account::class, 'update_raddress']);
        Route::post('update-baddress', [Account::class, 'update_baddress']);
        Route::post('update-saddress', [Account::class, 'update_saddress']);
        Route::post('change-password', [Account::class, 'change_password']);
    });

    Route::get('account', [Account::class, 'fetch_profile']);
    Route::get('wishlist', [Wishlist::class, 'wishlist_items']);

    Route::match(['get', 'post'], 'product-review/{id}', [Review::class, 'product_review'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::match(['get', 'post'], 'edit-review/{id}', [Review::class, 'edit_review'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::get('delete-review/{id}', [Review::class, 'delete_review'])->withoutMiddleware([VerifyCsrfToken::class]);

    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('submit-checkout', [CheckoutController::class, 'submit_checkout'])->name('submitcheckout');
        
    });

    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('invoice/{order_key}', [OrderController::class, 'invoice'])->name('invoice')->whereAlphaNumeric('order_key'); 

        Route::post('returnorder', [OrderController::class, 'returnOrder'])->name('returnorder');
    });
});

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('response', [CheckoutController::class, 'shurjoPaymentResponse'])->name('response');
    Route::get('cancel', [CheckoutController::class, 'shurjoPaymentCancel'])->name('cancel');
});

Route::prefix('ajax')->withoutMiddleware([VerifyCsrfToken::class])->group(function()
{
    Route::post('switch-product-variant', [AJAX::class, 'product_variant_data']);

    Route::prefix('user')->middleware('user.afterlogin')->group(function()
    {
        Route::post('add-to-cart', [AJAX::class, 'add_to_cart'])->withoutMiddleware([UserAfterLogin::class]);
        Route::post('update-cart-item', [AJAX::class, 'update_cart_item'])->withoutMiddleware([UserAfterLogin::class]);
        Route::post('remove-cart-item', [AJAX::class, 'remove_cart_item'])->withoutMiddleware([UserAfterLogin::class]);
        Route::post('add-remove-wishlist-item', [AJAX::class, 'add_remove_wishlist_item']);
        Route::post('apply-coupon', [AJAX::class, 'apply_coupon']);
        Route::get('remove-coupon', [AJAX::class, 'remove_coupon']);
        Route::get('checkout-verify', [AJAX::class, 'checkout_verify']);
    });
});

Route::match(['get', 'post'], 'vendor-signup', [Web::class, 'vendorSignup'])->name('vendorsignup');

Route::prefix('search')->name('search.')->group(function () {
    Route::get('/{key}', [Web::class, 'searchIndex'])->name('index');
    Route::post('submit', [Web::class, 'submitSearch'])->name('submit');
});

Route::match(['get', 'post'], 'reset-password', [Web::class, 'resetPassword'])->name('resetpassword');



Route::get('foo', function () {
    //  \Illuminate\Support\Facades\Artisan::call('view:clear');
    //  \Illuminate\Support\Facades\Artisan::call('route:clear');
    //  \Illuminate\Support\Facades\Artisan::call('config:clear');
    //  \Illuminate\Support\Facades\Artisan::call('cache:clear');

    \Illuminate\Support\Facades\Artisan::call('storage:link');
    // \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed');
});

?>