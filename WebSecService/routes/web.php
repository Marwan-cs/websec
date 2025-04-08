<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Route::get('/register', [UsersController::class, 'register'])->name('register');
Route::post('/register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('/login', [UsersController::class, 'login'])->name('login');
Route::post('/login', [UsersController::class, 'doLogin'])->name('do_login');

Route::get('/logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('/users', [UsersController::class, 'list'])->name('users');
Route::get('/profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('/users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('/users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('/users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('/users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('/users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

Route::prefix('products')->group(function () {
    // Public routes
    Route::get('/', [ProductsController::class, 'list'])
        ->name('products_list');

    // Protected routes
    Route::middleware(['auth'])->group(function () {
        // Purchase routes
        Route::post('/purchase/{product}', [ProductsController::class, 'purchase'])
            ->name('products.purchase')
            ->middleware('permission:purchase_products');

        // Stock management routes
        Route::middleware(['role:Admin|Employee'])->group(function () {
            Route::put('/update-stock/{product}', [ProductsController::class, 'updateStock'])
                ->name('products.update_stock')
                ->middleware('permission:edit_stock');
        });

        // Product management routes
        Route::middleware(['permission:edit_products'])->group(function () {
            Route::get('/edit/{product?}', [ProductsController::class, 'edit'])
                ->name('products_edit');
            Route::post('/save/{product?]', [ProductsController::class, 'save'])
                ->name('products_save');
        });

        Route::get('/delete/{product}', [ProductsController::class, 'delete'])
            ->name('products_delete')
            ->middleware('permission:delete_products');
    });
});

Route::prefix('customer')->middleware(['auth', 'role:Customer'])->group(function () {
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile')->middleware('permission:view_profile');
    Route::get('/purchases', [CustomerController::class, 'purchases'])->name('customer.purchases')->middleware('permission:view_purchases');
    Route::post('/purchase', [CustomerController::class, 'purchase'])->name('customer.purchase')->middleware('permission:purchase_products');
});

Route::group([
    'prefix' => 'employee',
    'as' => 'employee.',
    'middleware' => ['auth', 'role:Employee|Admin']
], function () {
    Route::get('/products', [EmployeeController::class, 'products'])->name('products')->middleware('permission:view_stock');
    Route::post('/products', [EmployeeController::class, 'addProduct'])->name('products.add')->middleware('permission:add_stock');
    Route::put('/products/{id}', [EmployeeController::class, 'editProduct'])->name('products.edit')->middleware('permission:edit_stock');
    Route::delete('/products/{id}', [EmployeeController::class, 'deleteProduct'])->name('products.delete')->middleware('permission:delete_stock');
    Route::get('/customers', [EmployeeController::class, 'customers'])->name('customers')->middleware('permission:view_customers');
    Route::post('/customers/{id}/credit', [EmployeeController::class, 'addCredit'])->name('customers.credit')->middleware('permission:add_credit');
    Route::put('/customers/{id}/credit', [EmployeeController::class, 'editCredit'])->name('customers.edit-credit')->middleware('permission:edit_credit');
    Route::delete('/customers/{id}/credit', [EmployeeController::class, 'deleteCredit'])->name('customers.delete-credit')->middleware('permission:delete_credit');
});

Route::prefix('admin')->middleware(['auth', 'role:Admin'])->name('admin.')->group(function () {
    Route::post('/employees', [AdminController::class, 'createEmployee'])->name('employees.create');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number ?? 5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');