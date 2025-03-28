<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Ad

// User management routes with permissions
Route::get('/users', [UserController::class, 'index'])->middleware('permission:view users')->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:create users')->name('users.create');
Route::post('/users', [UserController::class, 'store'])->middleware('permission:create users')->name('users.store');
Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:view users')->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:edit users')->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:edit users')->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete users')->name('users.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('posts', PostController::class);
    Route::resource('users', UserController::class);
});

