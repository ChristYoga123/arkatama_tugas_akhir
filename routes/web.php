<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortenLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class,'index'])->name("home.index");
Route::get('/s/{shortened_url}', [ShortenLinkController::class,'show'])->name("home.show");
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware("role:Super Admin|Admin");

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shorten Link Route
    Route::get("/shorten-link", [ShortenLinkController::class, "index"])->name("shorten-link.index");
    Route::post("/shorten-link", [ShortenLinkController::class, "store"])->name("shorten-link.store");
    Route::get("/shorten-link/{link}", [ShortenLinkController::class, "single"])->name("shorten-link.single");
    Route::delete("/shorten-link/{link}", [ShortenLinkController::class, "destroy"])->name("shorten-link.destroy");
});

require __DIR__.'/auth.php';
