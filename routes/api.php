<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Sistem Informasi Manajemen Tugas Mahasiswa
|
*/

// ============================================================
// A. PUBLIK — Tidak memerlukan autentikasi
// ============================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);


// ============================================================
// B. TERLINDUNGI — Memerlukan Bearer Token Sanctum
// ============================================================
Route::middleware('auth:sanctum')->group(function () {

    // Profil & Logout
    Route::get('/user',    [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD Tugas
    Route::get('/tasks',                    [TaskController::class, 'index']);
    Route::post('/tasks',                   [TaskController::class, 'store']);
    Route::get('/tasks/{id}',               [TaskController::class, 'show']);
    Route::match(['put', 'patch'], '/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}',            [TaskController::class, 'destroy']);
    Route::patch('/tasks/{id}/complete',    [TaskController::class, 'complete']);


    // ============================================================
    // C. KHUSUS ADMIN — Tambahan middleware admin
    // ============================================================
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/tasks', [AdminController::class, 'tasks']);
    });
});
