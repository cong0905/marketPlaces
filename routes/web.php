<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products (public)
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

// Static Pages
Route::get('/gioi-thieu', [\App\Http\Controllers\PageController::class, 'about'])->name('pages.about');
Route::get('/lien-he', [\App\Http\Controllers\PageController::class, 'contact'])->name('pages.contact');
Route::get('/trung-tam-tro-giup', [\App\Http\Controllers\PageController::class, 'help'])->name('pages.help');
Route::get('/quy-dinh-an-toan', [\App\Http\Controllers\PageController::class, 'safety'])->name('pages.safety');
Route::get('/dieu-khoan-su-dung', [\App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');
Route::get('/chinh-sach-bao-mat', [\App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');

// Public Profiles
Route::get('/nguoi-dung/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');

// VNPay IPN Route (Public access for server-to-server callback)
Route::get('/thanh-toan/vnpay/ipn', [\App\Http\Controllers\PaymentController::class, 'vnpayIpn'])->name('payment.vnpay.ipn');

// ── Authenticated Routes ───────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Checkout
    Route::get('/san-pham/{product}/dat-mua', [\App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/san-pham/{product}/dat-mua', [\App\Http\Controllers\CheckoutController::class, 'store'])
        ->middleware('throttle:checkout')
        ->name('checkout.store');
    Route::get('/dat-mua/thanh-cong/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    
    // VNPay Payment Routes
    Route::get('/thanh-toan/vnpay/return', [\App\Http\Controllers\PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');

    // Chat
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/bat-dau/{product}', [\App\Http\Controllers\ChatController::class, 'showByProduct'])->name('chat.start');
    Route::get('/chat/{conversation}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}', [\App\Http\Controllers\ChatController::class, 'store'])
        ->middleware('throttle:chat')
        ->name('chat.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Favorites
    Route::post('/yeu-thich/{product}', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Product CRUD (authenticated)
    Route::get('/dang-tin', [ProductController::class, 'create'])->name('products.create');
    Route::post('/dang-tin', [ProductController::class, 'store'])
        ->middleware('throttle:product-create')
        ->name('products.store');
    Route::get('/san-pham/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/san-pham/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/san-pham/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Order Actions
    Route::put('/don-hang/{order}/xac-nhan', [\App\Http\Controllers\OrderController::class, 'confirm'])->name('orders.confirm');
    Route::put('/don-hang/{order}/giao-hang', [\App\Http\Controllers\OrderController::class, 'ship'])->name('orders.ship');
    Route::put('/don-hang/{order}/hoan-thanh', [\App\Http\Controllers\OrderController::class, 'complete'])->name('orders.complete');
    Route::put('/don-hang/{order}/huy', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');

    // Notifications
    Route::post('/thong-bao/{notification}/doc', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/thong-bao/doc-tat-ca', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// ── Admin Routes ───────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý tin đăng (Products Moderation)
    Route::get('/san-pham', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/san-pham/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    Route::put('/san-pham/{product}/approve', [\App\Http\Controllers\Admin\ProductController::class, 'approve'])->name('products.approve');
    Route::put('/san-pham/{product}/reject', [\App\Http\Controllers\Admin\ProductController::class, 'reject'])->name('products.reject');

    // Quản lý danh mục (Categories)
    Route::resource('danh-muc', \App\Http\Controllers\Admin\CategoryController::class)
        ->parameters(['danh-muc' => 'category'])
        ->names('categories');

    // Báo cáo vi phạm (Reports)
    Route::get('/bao-cao', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::put('/bao-cao/{report}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');

    // Quản lý Người dùng (Users)
    Route::get('/nguoi-dung', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::put('/nguoi-dung/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'toggleBan'])->name('users.toggle-ban');

});

// ── User Report Routes ─────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/bao-cao/san-pham/{product}', [\App\Http\Controllers\ReportController::class, 'storeProductReport'])->name('reports.store.product');
    Route::post('/bao-cao/nguoi-dung/{user}', [\App\Http\Controllers\ReportController::class, 'storeUserReport'])->name('reports.store.user');
    
    // Reviews
    Route::post('/don-hang/{order}/danh-gia', [\App\Http\Controllers\ReviewController::class, 'store'])
        ->middleware('throttle:review')
        ->name('reviews.store');
});

require __DIR__.'/auth.php';
