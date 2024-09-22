<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\TagController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {

    // Rotte per Post
    Route::resource('/posts', PostController::class);
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');


    Route::middleware('role:admin|editor')->group(function () {
        Route::get('/drafts', [PostController::class, 'drafts'])->name('posts.drafts');
        Route::get('/posts/publish/{post:slug}', [PostController::class, 'publish'])->name('posts.publish');

        Route::get('/trash', [PostController::class, 'trash'])->name('posts.trash');
        Route::put('/trash/restore/{post:slug}', [PostController::class, 'restore'])->name('posts.restore');


    });

    // Rotte per Profilo
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotte per Tag con Permessi
    Route::middleware('role:admin')->group(function () {
        Route::resource('/tags', TagController::class)->except(['show', 'edit']);
    });


});

require __DIR__.'/auth.php';


