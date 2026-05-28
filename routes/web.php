<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivity;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AiController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalytics;
use App\Http\Controllers\Admin\BackupController as AdminBackup;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\ChatLogController as AdminChat;
use App\Http\Controllers\Admin\CouponController as AdminCoupon;
use App\Http\Controllers\Admin\AbandonedCartController as AdminAbandonedCart;
use App\Http\Controllers\Admin\CustomerController as AdminCustomer;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EmailCampaignController as AdminEmailCampaign;
use App\Http\Controllers\Admin\MessageController as AdminMessage;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\ReferralController as AdminReferral;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\ReturnController as AdminReturn;
use App\Http\Controllers\Admin\ReviewController as AdminReview;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\Admin\ShippingController as AdminShipping;
use App\Http\Controllers\Admin\StaffController as AdminStaff;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ChatbotController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProductRequestController;
use App\Http\Controllers\Frontend\ReturnController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\StaticPageController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SeoController;
use App\Http\Middleware\CheckNotInstalled;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Setup Installer (locked after first run)
|--------------------------------------------------------------------------
*/
Route::middleware(CheckNotInstalled::class)
    ->prefix('install')
    ->name('install.')
    ->group(function () {
        Route::get('/', [InstallController::class, 'index'])->name('index');
        Route::get('/preflight', [InstallController::class, 'preflight'])->name('preflight');
        Route::post('/test-database', [InstallController::class, 'testDatabase'])->name('test-database');
        Route::post('/run', [InstallController::class, 'run'])->name('run');
    });

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Referral code AJAX check (public — used by register form)
Route::get('/referral/check', function (Request $request) {
    $code = strtoupper(trim($request->query('code', '')));
    $valid = $code && User::where('referral_code', $code)->exists();

    return response()->json(['valid' => $valid]);
})->middleware('throttle:20,1')->name('referral.check');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::post('/chatbot', [ChatbotController::class, 'send'])->name('chatbot.send');
Route::get('/chatbot/history', [ChatbotController::class, 'history'])->name('chatbot.history');

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/shipping-rate', [CheckoutController::class, 'shippingRate'])->name('checkout.shipping-rate');
});
Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])
    ->middleware(['honeypot', 'throttle:5,10'])
    ->name('checkout.place');

Route::get('/order/success/{orderNumber}', [OrderController::class, 'success'])->name('order.success');
Route::get('/track-order', [OrderController::class, 'trackForm'])->name('track-order');
Route::post('/track-order', [OrderController::class, 'track'])->middleware('throttle:10,5')->name('track-order.submit');

// Static pages
Route::get('/about', [StaticPageController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware(['honeypot', 'throttle:3,60'])->name('contact.store');
Route::get('/faq', [StaticPageController::class, 'faq'])->name('faq');
Route::get('/shipping-returns', [StaticPageController::class, 'shippingReturns'])->name('shipping-returns');
Route::get('/privacy-policy', [StaticPageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [StaticPageController::class, 'terms'])->name('terms');
Route::get('/cookie-policy', [StaticPageController::class, 'cookiePolicy'])->name('cookie-policy');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware(['honeypot', 'throttle:5,60'])->name('newsletter.subscribe');

// PWA
Route::get('/offline', fn () => view('offline'))->name('offline');

// SEO
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Blog (public)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/request-product', [ProductRequestController::class, 'create'])->name('product-request.create');
Route::post('/request-product', [ProductRequestController::class, 'store'])->middleware(['throttle:5,60'])->name('product-request.store');

// Payment callbacks
Route::get('/payment/callback', [PaymentController::class, 'handleGatewayCallback'])->name('payment.callback');

/*
|--------------------------------------------------------------------------
| Account Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'overview'])->name('overview');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [AccountController::class, 'orderDetail'])->name('order.detail');
    Route::get('/orders/{orderNumber}/invoice', [AccountController::class, 'downloadInvoice'])->name('order.invoice');
    Route::get('/track', [AccountController::class, 'track'])->name('track');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::patch('/addresses/{address}/default', [AccountController::class, 'setDefaultAddress'])->name('addresses.default');
    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{product}', [AccountController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/reviews', [AccountController::class, 'reviews'])->name('reviews');
    Route::post('/reviews', [AccountController::class, 'storeReview'])->name('reviews.store');

    Route::post('/returns/{orderNumber}', [ReturnController::class, 'store'])->name('returns.store');

    // Profile requires verified email — protects password/email changes
    Route::middleware('verified')->group(function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

        // Orders
        Route::middleware('role_or_permission:super_admin|admin|orders.view')->group(function () {
            Route::resource('orders', AdminOrder::class)->only(['index', 'show', 'update']);
            Route::patch('orders/{order}/status', [AdminOrder::class, 'updateStatus'])->name('orders.status');
            Route::get('orders/{order}/invoice', [AdminOrder::class, 'invoice'])->name('orders.invoice');
        });

        // Products
        Route::middleware('role_or_permission:super_admin|admin|products.view')->group(function () {
            Route::get('products/low-stock', [AdminProduct::class, 'lowStock'])->name('products.low-stock');
            Route::get('stock/reservations', [AdminProduct::class, 'stockReservations'])->name('stock.reservations');
            Route::resource('products', AdminProduct::class);
            Route::patch('products/{product}/toggle', [AdminProduct::class, 'toggle'])->name('products.toggle');
            Route::delete('products/images/{image}', [AdminProduct::class, 'deleteImage'])->name('products.images.delete');
        });

        // Categories
        Route::middleware('role_or_permission:super_admin|admin|categories.manage')->group(function () {
            Route::resource('categories', AdminCategory::class);
        });

        // Shipping zones
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('shipping', [AdminShipping::class, 'index'])->name('shipping.index');
            Route::get('shipping/create', [AdminShipping::class, 'create'])->name('shipping.create');
            Route::post('shipping', [AdminShipping::class, 'store'])->name('shipping.store');
            Route::get('shipping/{shipping}/edit', [AdminShipping::class, 'edit'])->name('shipping.edit');
            Route::put('shipping/{shipping}', [AdminShipping::class, 'update'])->name('shipping.update');
            Route::delete('shipping/{shipping}', [AdminShipping::class, 'destroy'])->name('shipping.destroy');
            Route::patch('shipping/{shipping}/toggle', [AdminShipping::class, 'toggle'])->name('shipping.toggle');
        });

        // Backups (super_admin only)
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('backups', [AdminBackup::class, 'index'])->name('backups.index');
            Route::post('backups/run', [AdminBackup::class, 'run'])->name('backups.run');
            Route::get('backups/download', [AdminBackup::class, 'download'])->name('backups.download');
            Route::delete('backups', [AdminBackup::class, 'destroy'])->name('backups.destroy');
        });

        // Customers
        Route::middleware('role_or_permission:super_admin|admin|users.view')->group(function () {
            Route::get('customers', [AdminCustomer::class, 'index'])->name('customers.index');
            Route::get('customers/{user}', [AdminCustomer::class, 'show'])->name('customers.show');
            Route::patch('customers/{user}/block', [AdminCustomer::class, 'toggleBlock'])->name('customers.block');
            Route::post('customers/{user}/email', [AdminCustomer::class, 'sendEmail'])->name('customers.email');

            // Abandoned Carts
            Route::get('abandoned-carts', [AdminAbandonedCart::class, 'index'])->name('abandoned-carts.index');
            Route::post('abandoned-carts/send-all', [AdminAbandonedCart::class, 'sendAll'])->name('abandoned-carts.send-all');
            Route::post('abandoned-carts/{cart}/remind', [AdminAbandonedCart::class, 'sendReminder'])->name('abandoned-carts.remind');
            Route::delete('abandoned-carts/{cart}', [AdminAbandonedCart::class, 'destroy'])->name('abandoned-carts.destroy');

            // Wishlists
            Route::get('wishlists', [\App\Http\Controllers\Admin\WishlistController::class, 'index'])->name('wishlists.index');
        });

        // Referrals
        Route::middleware('role_or_permission:super_admin|admin')->group(function () {
            Route::get('referrals', [AdminReferral::class, 'index'])->name('referrals.index');
            Route::get('referrals/settings', [AdminReferral::class, 'settingsPage'])->name('referrals.settings');
            Route::put('referrals/settings', [AdminReferral::class, 'settingsUpdate'])->name('referrals.settings.update');

            // Returns
            Route::get('returns', [AdminReturn::class, 'index'])->name('returns.index');
            Route::get('returns/{return}', [AdminReturn::class, 'show'])->name('returns.show');
            Route::patch('returns/{return}', [AdminReturn::class, 'update'])->name('returns.update');
        });

        // Coupons
        Route::middleware('role_or_permission:super_admin|admin|coupons.manage')->group(function () {
            Route::resource('coupons', AdminCoupon::class);
        });

        // Reviews
        Route::middleware('role_or_permission:super_admin|admin|reviews.moderate')->group(function () {
            Route::get('reviews', [AdminReview::class, 'index'])->name('reviews.index');
            Route::patch('reviews/{review}/approve', [AdminReview::class, 'approve'])->name('reviews.approve');
            Route::delete('reviews/{review}', [AdminReview::class, 'destroy'])->name('reviews.destroy');
        });

        // Messages
        Route::middleware('role_or_permission:super_admin|admin|messages.respond')->group(function () {
            Route::get('messages', [AdminMessage::class, 'index'])->name('messages.index');
            Route::get('messages/{message}', [AdminMessage::class, 'show'])->name('messages.show');
            Route::patch('messages/{message}/read', [AdminMessage::class, 'markRead'])->name('messages.read');
            Route::post('messages/{message}/reply', [AdminMessage::class, 'reply'])->name('messages.reply');
        });

        // Email Campaigns (admin/super_admin only)
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('email-campaigns', [AdminEmailCampaign::class, 'index'])->name('email-campaigns.index');
            Route::post('email-campaigns/new-product', [AdminEmailCampaign::class, 'sendNewProduct'])->name('email-campaigns.new-product');
            Route::post('email-campaigns/wishlist-reminder', [AdminEmailCampaign::class, 'sendWishlistReminder'])->name('email-campaigns.wishlist-reminder');
            Route::post('email-campaigns/cart-reminder', [AdminEmailCampaign::class, 'sendCartReminder'])->name('email-campaigns.cart-reminder');
            Route::post('email-campaigns/festive', [AdminEmailCampaign::class, 'sendFestive'])->name('email-campaigns.festive');
            Route::post('email-campaigns/new-month', [AdminEmailCampaign::class, 'sendNewMonth'])->name('email-campaigns.new-month');
        });

        // Chat Logs
        Route::middleware('role_or_permission:super_admin|admin|chat.view')->group(function () {
            Route::get('chat-logs', [AdminChat::class, 'index'])->name('chat.index');
            Route::get('chat-logs/{session}', [AdminChat::class, 'show'])->name('chat.show');
            Route::delete('chat-logs/{session}', [AdminChat::class, 'destroy'])->name('chat.destroy');
        });

        // Reports
        Route::middleware('role_or_permission:super_admin|admin|reports.view')->group(function () {
            Route::get('reports', [AdminReport::class, 'index'])->name('reports.index');
            Route::get('reports/export', [AdminReport::class, 'export'])->name('reports.export');
        });

        // Staff (super_admin only)
        Route::middleware('role:super_admin')->group(function () {
            Route::get('staff', [AdminStaff::class, 'index'])->name('staff.index');
            Route::post('staff/invite', [AdminStaff::class, 'invite'])->name('staff.invite');
            Route::patch('staff/{user}/role', [AdminStaff::class, 'changeRole'])->name('staff.role');
            Route::patch('staff/{user}/toggle', [AdminStaff::class, 'toggle'])->name('staff.toggle');
            Route::put('staff/{user}/permissions', [AdminStaff::class, 'updatePermissions'])->name('staff.permissions');
            Route::delete('staff/{user}', [AdminStaff::class, 'destroy'])->name('staff.destroy');
        });

        // Settings
        Route::middleware('role_or_permission:super_admin|admin|settings.manage')->group(function () {
            Route::get('settings', [AdminSetting::class, 'index'])->name('settings.index');
            Route::put('settings', [AdminSetting::class, 'update'])->name('settings.update');
        });

        // Activity Log (admin/super_admin only)
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('activity', [AdminActivity::class, 'index'])->name('activity.index');
            Route::delete('activity/clear', [AdminActivity::class, 'clear'])->name('activity.clear');
        });

        // Newsletter (admin/super_admin only)
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('newsletter', [App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
            Route::get('newsletter/export', [App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletter.export');
            Route::post('newsletter/broadcast', [App\Http\Controllers\Admin\NewsletterController::class, 'broadcast'])->name('newsletter.broadcast');
        });

        // Blog (admin/super_admin only)
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::resource('blog', App\Http\Controllers\Admin\BlogController::class);
        });

        // Product Requests
        Route::get('product-requests', [App\Http\Controllers\Admin\ProductRequestController::class, 'index'])->name('product-requests.index');
        Route::get('product-requests/{productRequest}', [App\Http\Controllers\Admin\ProductRequestController::class, 'show'])->name('product-requests.show');
        Route::patch('product-requests/{productRequest}', [App\Http\Controllers\Admin\ProductRequestController::class, 'update'])->name('product-requests.update');
        Route::delete('product-requests/{productRequest}', [App\Http\Controllers\Admin\ProductRequestController::class, 'destroy'])->name('product-requests.destroy');

        // AI Studio
        Route::middleware('role_or_permission:super_admin|admin|chat.view')->group(function () {
            Route::get('ai', [AiController::class, 'index'])->name('ai.assistant');
            Route::post('ai/gemini', [AiController::class, 'gemini'])->name('ai.gemini');
            Route::post('ai/groq', [AiController::class, 'groq'])->name('ai.groq');
        });

        // Pages (admin/super_admin only)
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('pages', [PageController::class, 'index'])->name('pages.index');
            Route::put('pages', [PageController::class, 'update'])->name('pages.update');
        });

        // Cache clear (admin/super_admin only)
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::post('cache/clear', [SettingController::class, 'clearCache'])->name('cache.clear');
        });

        // Google Analytics
        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('analytics', [AdminAnalytics::class, 'index'])->name('analytics.index');
        });
    });

/*
|--------------------------------------------------------------------------
| Admin Auth Routes (separate from customer auth)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout')->middleware('auth');
});

require __DIR__.'/auth.php';
