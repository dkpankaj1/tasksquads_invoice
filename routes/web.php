<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Ajax\CreateCustomerController;
use App\Http\Controllers\Ajax\CreateItemController;
use App\Http\Controllers\Ajax\GetItemController;
use App\Http\Controllers\Ajax\SearchItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceYearController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PDF\InvoicePdfController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

// guest route
Route::group(['middleware' => 'guest'], function () {

    Route::get('/', fn () => redirect()->route('login'));

    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store']);
});

// auth Route
Route::group(['middleware' => 'auth'], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('item', ItemController::class)
        ->except(['show']);

    Route::resource('category', CategoryController::class)
        ->except(['show']);

    Route::resource('customer', CustomerController::class);

    Route::resource('finance-year', FinanceYearController::class)
        ->parameters(['finance_year' => 'financeYear']);

    Route::resource('invoice', InvoiceController::class);
    // Invoice PDF
    Route::get('invoice/{invoice}/pdf', InvoicePdfController::class)->name('invoice.pdf');

    Route::resource('payment', PaymentController::class);

    Route::resource('unit', UnitController::class)
        ->except(['show']);

    Route::resource('tax', TaxController::class)
        ->except(['show']);

    Route::resource('customization', CustomizationController::class)
        ->except(['create', 'store', 'show']);

    Route::post('themes/toggle', [ThemeController::class, 'toggle'])->name('themes.toggle');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/edit', [SettingController::class, 'edit'])->name('edit');
        Route::put('/edit', [SettingController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'system-settings', 'as' => 'systemSettings.'], function () {
        Route::get('/edit', [SystemSettingController::class, 'edit'])->name('edit');
        Route::put('/edit', [SystemSettingController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('update', [AccountController::class, 'profile'])->name('update');
        Route::put('/update', [AccountController::class, 'profileUpdate']);
        Route::get('/password', [AccountController::class, 'password'])->name('password');
        Route::patch('/password', [AccountController::class, 'passwordUpdate']);
    });

    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
    Route::delete('logout/{session_id}/session', [AuthController::class, 'destroySession'])->name('logout.session');

    /** Ajax Route::begin */
    Route::get('ajax/search-item', SearchItemController::class)->name('ajax.searchItem');
    Route::get('ajax/get-item', GetItemController::class)->name('ajax.getItem');

    Route::post('ajax/create-customer', CreateCustomerController::class)->name('ajax.createCustomer');
    Route::post('ajax/create-item', CreateItemController::class)->name('ajax.createItem');
    /** Ajax Route::end */
});
