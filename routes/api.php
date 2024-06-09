<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
//use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\WantToReadController;

Route::middleware('auth:sanctum')->group(function () {
    
    Route::middleware('role:admin')->group(function () {
        //Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/users', [AdminController::class, 'addUser'])->name('admin.addUser');
        Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.listUsers');
        Route::get('/admin/users/{id}', [AdminController::class, 'showUser'])->name('admin.showUser');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::post('/books', [BookController::class, 'store'])->name('store');
        Route::put('/books/update', [BookController::class, 'update'])->name('update');
        Route::delete('/books/delete', [BookController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:user')->group(function () {
        //Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::post('/books/{bookId}/want-to-read', [WantToReadController::class, 'addToWantToRead']);
        Route::delete('/books/{bookId}/want-to-read', [WantToReadController::class, 'removeFromWantToRead']);
        Route::get('/books/want-to-read', [WantToReadController::class, 'getBooksFromWantToRead']);
    });

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

});

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/books', [BookController::class, 'index'])->name('index');
Route::get('/books/search', [BookController::class, 'show'])->name('show');
