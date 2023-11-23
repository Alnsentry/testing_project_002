<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\IndexController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    $controller_path = 'App\Http\Controllers';


    Route::get('/dashboard', [IndexController::class, 'index'])->name('dashboard.index');

    Route::prefix('accounts')->group(function () {
        Route::resource('personnels', StaffController::class);
        Route::resource('access-rights', RoleController::class);
    });

    Route::prefix('books')->group(function () {
        Route::resource('books', BookController::class);
        Route::resource('submissions', SubmissionController::class);
        Route::get('submission-histories', [SubmissionController::class, 'history'])->name('histories.index');
        Route::get('borrowings', [SubmissionController::class, 'borrowing'])->name('borrowings.index');
        Route::get('borrowing-books', [SubmissionController::class, 'borrowingBook'])->name('borrowing-books.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
