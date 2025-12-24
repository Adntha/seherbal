<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlantController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- JALUR KHUSUS ADMIN (Protected) ---
// Harus bawa TOKEN untuk masuk sini
Route::middleware('auth:sanctum')->group(function () {
    
    // CRUD Tanaman
    Route::post('/plants', [PlantController::class, 'store']);      // Tambah
    Route::post('/plants/{id}', [PlantController::class, 'update']); // Edit (PENTING: Pakai POST untuk upload file di Laravel, bukan PUT)
    Route::delete('/plants/{id}', [PlantController::class, 'destroy']); // Hapus

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/plants', [PlantController::class, 'index']);
Route::get('/plants/{id}', [PlantController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);

// Chatbot Routes
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage']);
Route::get('/chatbot/suggestions', [ChatbotController::class, 'getSuggestions']);
