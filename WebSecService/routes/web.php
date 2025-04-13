<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\ProductsController;

// Static Pages
Route::get('/', function () {
    return view('welcome');
});
Route::get('/even', function () {
    return view('even');
});
Route::get('/prime', function () {
    return view('prime');
});
Route::get('/multable/{number?}', function ($number = 10) {
    $j = $number;
    return view('multable', compact("j"));
});
Route::get('/minitest', function () {
    $bills = [
        ['item' => 'Apples', 'quantity' => 2, 'price' => 3.50],
        ['item' => 'Bread', 'quantity' => 1, 'price' => 2.00],
        ['item' => 'Milk', 'quantity' => 1, 'price' => 2.75],
        ['item' => 'Cheese', 'quantity' => 1, 'price' => 5.00],
    ];
    return view('minitest', compact("bills"));
});

// Web Authentication
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Forgot Password Routes
Route::get('forgot-password', [UsersController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('forgot-password', [UsersController::class, 'sendTemporaryPassword'])->name('forgot.password.submit');
Route::get('change-password', [UsersController::class, 'showChangePassword'])->name('change.password')->middleware('auth');
Route::post('change-password', [UsersController::class, 'changePassword'])->name('change.password.submit')->middleware('auth');



// Users Management
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

Route::get('/admin/employees/create', [UsersController::class, 'createEmployee'])->name('create_employee')->middleware('role:admin');
Route::post('/admin/employees/store', [UsersController::class, 'storeEmployee'])->name('store_employee')->middleware('role:admin');

// Employee Features
Route::get('users/customers', [UsersController::class, 'listCustomers'])->name('list_customers');
Route::get('users/add_credit/{user}', [UsersController::class, 'addCredit'])->name('add_credit');
Route::post('users/save_credit/{user}', [UsersController::class, 'saveCredit'])->name('save_credit');

// Products Management
Route::get('products', [ProductsController::class, 'list'])->name('products.list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

Route::get('purchases', [ProductsController::class, 'myPurchases'])->name('products_purchases');

Route::get('products/buy/{product}', [ProductsController::class, 'confirm'])->name('products_buy_confirm');
Route::post('products/buy/{product}', [ProductsController::class, 'buy'])->name('products_buy');

Route::get('users/{user}/credit', [UsersController::class, 'addCredit'])->name('add_credit');
Route::post('users/{user}/credit', [UsersController::class, 'saveCredit'])->name('save_credit');

// Email Verification
Route::get('verify', [UsersController::class, 'verify'])->name('verify');