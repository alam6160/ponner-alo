<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Events\RouteMatched;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AjaxDeleteController;
use App\Http\Controllers\Admin\ManageAgentController;
use App\Http\Controllers\Admin\ManageOrderController;
use App\Http\Controllers\Admin\ManageStaffController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\ProductAddonController;
use App\Http\Controllers\Admin\ProductFilterController;
use App\Http\Controllers\Admin\ManageCustomerController;
use App\Http\Controllers\Admin\ManageRetailerController;
use App\Http\Controllers\Admin\ManageStateheadController;
use App\Http\Controllers\Admin\UserApplicationController;
use App\Http\Controllers\Admin\ManageDeliveryBoyController;
use App\Http\Controllers\Admin\ApplicationPaymentController;

Route::match(['get', 'post'], 'admin', [AuthController::class, 'index'])->name('admin')->middleware('admin.guest');

Route::prefix('admin')->name('admin.')->middleware('auth.admin')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::get('subscription-log', [MainController::class, 'subscriptionLog'])->name('subscriptionlog');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::match(['get', 'post'], 'edit', [MainController::class, 'edit_profile'])->name('edit');
        Route::match(['get', 'post'], 'change-password', [MainController::class, 'chagepassword'])->name('change-password');
    });

    Route::prefix('home')->name('home.')->group(function () {

        Route::match(['get', 'post'], 'site-setting', [MainController::class, 'siteSetting'])->name('sitesetting');
      	Route::post('uploadlogo', [MainController::class, 'uploadLogo'])->name('uploadlogo');

        Route::prefix('page')->name('page.')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [PageController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [PageController::class, 'edit'])->name('edit')->whereNumber('id');
        });
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/', [BlogController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [BlogController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [BlogController::class, 'edit'])->name('edit')->whereNumber('id');

            Route::prefix('category')->name('category.')->group(function () {
                Route::get('/', [BlogCategoryController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [BlogCategoryController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [BlogCategoryController::class, 'edit'])->name('edit')->whereNumber('id');
            });
        });

        Route::prefix('slider')->name('slider.')->group(function () {
            Route::get('/', [SliderController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [SliderController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [SliderController::class, 'edit'])->name('edit')->whereNumber('id');
        });

    });

    Route::prefix('payout')->name('payout.')->group(function () {
        Route::get('/', [PayoutController::class, 'index'])->name('index');
        Route::get('view/{id}', [PayoutController::class, 'view'])->name('view')->whereNumber('id');
        Route::post('cancel', [PayoutController::class, 'cancel'])->name('cancel');
        Route::post('approve', [PayoutController::class, 'approve'])->name('approve');
    });

    Route::prefix('manage-user')->name('manage-user.')->group(function () {

        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [ManageStaffController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageStaffController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageStaffController::class, 'edit'])->name('edit')->whereNumber('id');
            
        });

        /*
        Route::prefix('statehead')->name('statehead.')->group(function () {
            Route::get('/', [ManageStateheadController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageStateheadController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageStateheadController::class, 'edit'])->name('edit')->whereNumber('id');
        });
        */

        Route::prefix('agent')->name('agent.')->group(function () {
            Route::get('/', [ManageAgentController::class, 'index'])->name('index');
            Route::get('regular', [ManageAgentController::class, 'regular'])->name('regular');
            Route::get('subscription', [ManageAgentController::class, 'subscription'])->name('subscription');
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

        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/', [ManageCustomerController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ManageCustomerController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ManageCustomerController::class, 'edit'])->name('edit')->whereNumber('id');
        });
    });

    Route::prefix('product')->name('product.')->group(function () {
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');

            Route::match(['get', 'post'], 'create', [CategoryController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [CategoryController::class, 'edit'])->name('edit')->whereNumber('id');
        });

        Route::prefix('addon')->name('addon.')->group(function () {
            Route::get('/', [ProductAddonController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ProductAddonController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ProductAddonController::class, 'edit'])->name('edit')->whereNumber('id');
        });

        Route::prefix('filter')->name('filter.')->group(function () {
            Route::get('/', [ProductFilterController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [ProductFilterController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [ProductFilterController::class, 'edit'])->name('edit')->whereNumber('id');
        });

        Route::prefix('coupon')->name('coupon.')->group(function () {
            Route::get('/', [CouponController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [CouponController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [CouponController::class, 'edit'])->name('edit')->whereNumber('id');
        });

        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('log/{type}', [ProductController::class, 'list_product']);
        Route::match(['get', 'post'], 'view/{id}', [ProductController::class, 'view']);
        Route::get('status/{id}/{status}', [ProductController::class, 'admin_verify'])->withoutMiddleware([VerifyCsrfToken::class]);
        
        Route::match(['get', 'post'], 'create', [ProductController::class, 'create'])->name('create');
        Route::match(['get', 'post'], 'edit/{id}', [ProductController::class, 'edit'])->name('edit')->whereNumber('id');
        Route::post('delete-image', [ProductController::class, 'delete_image'])->name('deleteimage');
        Route::post('get-image', [ProductController::class, 'get_image'])->name('getimage');
    });

    Route::prefix('manage-order')->name('manageorder.')->group(function () {
        Route::get('/', [ManageOrderController::class, 'index'])->name('index');
        Route::get('recieved', [ManageOrderController::class, 'recieved'])->name('recieved');
        Route::get('processed', [ManageOrderController::class, 'processed'])->name('processed');
        Route::get('shipped', [ManageOrderController::class, 'shipped'])->name('shipped');
        Route::get('delivered', [ManageOrderController::class, 'delivered'])->name('delivered');
        Route::get('cancel', [ManageOrderController::class, 'cancel'])->name('cancel');
        Route::get('return', [ManageOrderController::class, 'return'])->name('return');
        Route::get('details/{order_key}', [ManageOrderController::class, 'details'])->name('details')->whereAlphaNumeric('order_key');

        Route::post('changestatus', [ManageOrderController::class, 'changestatus'])->name('changestatus');
        Route::post('cancel-order', [ManageOrderController::class, 'cancelOrder'])->name('cancelorder');
        Route::post('chagereturnstatus', [ManageOrderController::class, 'chageReturnStatus'])->name('chagereturnstatus');
        
    });

    

    /*
    Route::prefix('application')->name('application.')->group(function () {
        Route::get('/', [UserApplicationController::class, 'index'])->name('index');
        Route::get('view/{id}', [UserApplicationController::class, 'view'])->name('view')->whereNumber('id');
        Route::prefix('approve')->name('approve.')->group(function () {
            Route::post('satehead', [UserApplicationController::class, 'approve_satehead'])->name('satehead');
            Route::post('agent', [UserApplicationController::class, 'approve_agent'])->name('agent');
        });
        Route::post('cancel', [UserApplicationController::class, 'cancel'])->name('cancel');

        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/', [ApplicationPaymentController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'view/{id}', [ApplicationPaymentController::class, 'view'])->name('view')->whereNumber('id');
            Route::post('cancel', [ApplicationPaymentController::class, 'cancel'])->name('cancel');
            Route::post('approve', [ApplicationPaymentController::class, 'approve'])->name('approve');
        });
    }); */

    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::prefix('changestatus')->name('changestatus.')->group(function () {
            Route::post('active', [AjaxDeleteController::class, 'user_active'])->name('active');
            Route::post('user', [AjaxDeleteController::class, 'user_status'])->name('user');
            Route::post('category', [AjaxDeleteController::class, 'category_status'])->name('category');
            Route::post('product', [AjaxDeleteController::class, 'product_status'])->name('product');
            Route::post('blog', [AjaxDeleteController::class, 'blog_status'])->name('blog');
            Route::post('product-addon', [AjaxDeleteController::class, 'productAddon_status'])->name('productaddon');
            Route::post('product-filter', [AjaxDeleteController::class, 'productFilter_status'])->name('productfilter');
            Route::post('coupon', [AjaxDeleteController::class, 'coupon_status'])->name('coupon');
            Route::post('blog-category', [AjaxDeleteController::class, 'blogcategory_status'])->name('blogcategory');
            Route::post('slider', [AjaxDeleteController::class, 'slider_status'])->name('slider');
        });
        Route::post('search-products', [ProductController::class, 'searchProduct'])->name('searchproducts');
    });
});