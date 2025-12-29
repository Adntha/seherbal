<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\PlantController;



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

Route::get('/chatbot', [ChatbotController::class, 'index']);
Route::get('/', [TanamanController::class, 'index']);
// Pastikan URL-nya adalah /plants/all
Route::get('/plants/all', [App\Http\Controllers\TanamanController::class, 'loadAll']);

// Route untuk halaman detail tanaman
Route::get('/tanaman/{id}', [TanamanController::class, 'show'])->name('tanaman.detail');

// Route untuk user mengirim pesan contact (PUBLIC - tidak perlu login)
Route::post('/contact/send', [MessageController::class, 'store'])->name('contact.send');

// Menampilkan halaman login
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

// Memproses data login (saat tombol login diklik)
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Logout
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
// routes/web.php

Route::prefix('admin')->name('admin.')->group(function () {
    // Halaman Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Halaman Form Edit (Ini yang sering bikin 404 kalau parameternya tidak pas)
    Route::get('/plants/{id}/edit', [PlantController::class, 'edit'])->name('plants.edit-tanaman');
});

// Grup Route Admin (Hanya bisa diakses setelah LOGIN)
// File: routes/web.php

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [TanamanController::class, 'adminIndex'])->name('admin.dashboard');
    Route::get('/messages', [MessageController::class, 'index'])->name('admin.messages'); // Route baru
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/plants/create', [PlantController::class, 'create'])->name('admin.plants.create');
    Route::post('/plants/store', [PlantController::class, 'store'])->name('admin.plants.store');
});

