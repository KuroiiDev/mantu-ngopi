<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Manager
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Manager\UserController as ManagerUser;
use App\Http\Controllers\Manager\CategoryController as ManagerCategory;
use App\Http\Controllers\Manager\ProductController as ManagerProduct;
use App\Http\Controllers\Manager\SupplyController as ManagerSupply;
use App\Http\Controllers\Manager\RestockController as ManagerRestock;
use App\Http\Controllers\Manager\TransactionController as ManagerTransaction;
use App\Http\Controllers\Manager\PasswordResetRequestController as ManagerPasswordReset;

// Logistic
use App\Http\Controllers\Logistic\DashboardController as LogisticDashboard;
use App\Http\Controllers\Logistic\SupplyController as LogisticSupply;
use App\Http\Controllers\Logistic\RestockController as LogisticRestock;

// Cashier
use App\Http\Controllers\Cashier\DashboardController as CashierDashboard;
use App\Http\Controllers\Cashier\ProductController as CashierProduct;
use App\Http\Controllers\Cashier\TransactionController as CashierTransaction;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return match (auth()->user()->role) {
        'manager' => redirect()->route('manager.dashboard'),
        'cashier' => redirect()->route('cashier.dashboard'),
        'logistic' => redirect()->route('logistic.dashboard'),
        default => abort(403, 'Unauthorized'),
    };
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/change-password', function () {
        return match (auth()->user()->role) {
            'manager' => redirect()->route('manager.password'),
            'cashier' => redirect()->route('cashier.password'),
            'logistic' => redirect()->route('logistic.password'),
            default => abort(403),
        };
    })->name('change-password');
});

Route::prefix('manager')->middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/', [ManagerDashboard::class, 'index'])->name('manager.dashboard');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('manager.password');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('manager.password.update');


    Route::resource('users', ManagerUser::class)->names([
        'index' => 'manager.users.index',
        'create' => 'manager.users.create',
        'store' => 'manager.users.store',
        'show' => 'manager.users.show',
        'edit' => 'manager.users.edit',
        'update' => 'manager.users.update',
        'destroy' => 'manager.users.destroy',
    ]);

    Route::resource('password-reset-requests', ManagerPasswordReset::class)
        ->only(['update'])
        ->names([
            'update' => 'manager.password-reset-requests.update',
        ]);

    Route::resource('categories', ManagerCategory::class)->names([
        'index' => 'manager.categories.index',
        'create' => 'manager.categories.create',
        'store' => 'manager.categories.store',
        'show' => 'manager.categories.show',
        'edit' => 'manager.categories.edit',
        'update' => 'manager.categories.update',
        'destroy' => 'manager.categories.destroy',
    ]);

    Route::resource('products', ManagerProduct::class)->names([
        'index' => 'manager.products.index',
        'create' => 'manager.products.create',
        'store' => 'manager.products.store',
        'show' => 'manager.products.show',
        'edit' => 'manager.products.edit',
        'update' => 'manager.products.update',
        'destroy' => 'manager.products.destroy',
    ]);

    Route::resource('supplies', ManagerSupply::class)->names([
        'index' => 'manager.supplies.index',
        'create' => 'manager.supplies.create',
        'store' => 'manager.supplies.store',
        'show' => 'manager.supplies.show',
        'edit' => 'manager.supplies.edit',
        'update' => 'manager.supplies.update',
        'destroy' => 'manager.supplies.destroy',
    ]);

    Route::resource('restocks', ManagerRestock::class)
        ->only(['index', 'show', 'destroy'])
        ->names([
            'index' => 'manager.restocks.index',
            'show' => 'manager.restocks.show',
            'destroy' => 'manager.restocks.destroy',
        ]);

    Route::resource('transactions', ManagerTransaction::class)
        ->only(['index', 'show'])
        ->names([
            'index' => 'manager.transactions.index',
            'show' => 'manager.transactions.show',
        ]);
});


Route::prefix('logistic')->middleware(['auth', 'role:logistic'])->group(function () {
    Route::get('/', [LogisticDashboard::class, 'index'])->name('logistic.dashboard');
    Route::get('/change-password', [ProfileController::class, 'requestPassword'])->name('logistic.password');
    Route::post('/change-password', [ProfileController::class, 'storeRequest'])->name('logistic.password.store');

    Route::resource('supplies', LogisticSupply::class)
        ->only(['index', 'show'])
        ->names([
            'index' => 'logistic.supplies.index',
            'show' => 'logistic.supplies.show',
        ]);

    Route::resource('restocks', LogisticRestock::class)
        ->only(['index', 'show'])
        ->names([
            'index' => 'logistic.restocks.index',
            'show' => 'logistic.restocks.show',
        ]);

    Route::post('/supplies/{supply}/restock', [LogisticRestock::class, 'store'])->name('logistic.restocks.store');
});


Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/', [CashierDashboard::class, 'index'])->name('cashier.dashboard');
    Route::get('/change-password', [ProfileController::class, 'requestPassword'])->name('cashier.password');
    Route::post('/change-password', [ProfileController::class, 'storeRequest'])->name('cashier.password.store');
        
    Route::resource('products', CashierProduct::class)
    ->only(['index', 'show'])
    ->names([
        'index' => 'cashier.products.index',
        'show' => 'cashier.products.show',
        ]);
        
        Route::resource('transactions', CashierTransaction::class)
        ->only(['index', 'show', 'store', 'update'])
        ->names([
            'index' => 'cashier.transactions.index',
            'show' => 'cashier.transactions.show',
            'store' => 'cashier.transactions.store',
            'update' => 'cashier.transactions.update',
            ]);
        Route::get('/transactions/{transaction}/receipt', [CashierTransaction::class, 'receipt'])->name('cashier.transactions.receipt');
});