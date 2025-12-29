<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlantController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ChatbotController;


// Pastikan route ini sesuai dengan API_URL di JavaScript Anda
// Route::middleware('auth:sanctum')->group(function () {
//     Route::put('/plants/{id}', [PlantController::class, 'update']);
// });

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

// Public routes (tidak perlu login)
Route::get('/plants', [PlantController::class, 'index']);
Route::get('/plants/{id}', [PlantController::class, 'show']);

// Protected routes (perlu login admin dengan Sanctum token)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/plants', [PlantController::class, 'store']);
    Route::post('/plants/{id}', [PlantController::class, 'update']); // ✅ TAMBAH route update
    Route::delete('/plants/{id}', [PlantController::class, 'destroy']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Auth routes
Route::post('/login', [AuthController::class, 'login']);

// Chatbot Routes
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage']);
Route::get('/chatbot/suggestions', [ChatbotController::class, 'getSuggestions']);
