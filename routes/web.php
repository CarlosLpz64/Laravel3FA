<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\VerifyCode;
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


Route::get('/', function () {
    return view('welcome');
})->withoutMiddleware([VerifyCode::class])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['code', 'auth', 'verified'])->name('dashboard');

Route::middleware(['code', 'auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/notAllowed', function () {
    return view('notAllowed');
})->name('notAllowed');

// ->withoutMiddleware(['code'])
require __DIR__.'/auth.php';
