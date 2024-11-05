<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

require __DIR__ . '/main.php';

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('exhibition', ProductController::class);
Route::resource('services', ServiceController::class);
Route::resource('blog', PostController::class);

Route::get('epaphradito', [HomeController::class, 'epaphradito'])->name('epaphrdaito');
Route::get('privacy', [HomeController::class, ''])->name('privacy');

Route::get('about', [HomeController::class, 'about'])->name('about-us');

Route::middleware('admin')->group(function () {
  Route::resource('users', UserController::class);
})->prefix('admin');

require __DIR__ . '/auth.php';
