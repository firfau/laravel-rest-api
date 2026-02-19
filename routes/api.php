<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Data\DataController;

// ─── PUBLIC (tanpa token) ─────────────────────────────────────

// A. Endpoint Login
Route::post('/login', [AuthController::class, 'login']);

// ─── PROTECTED (wajib pakai Bearer Token) ────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // B. CRUD User
    Route::apiResource('users', UserController::class);

    // C. Cari by NAMA
    Route::get('/search/nama', [DataController::class, 'searchByNama']);

    // D. Cari by NIM
    Route::get('/search/nim',  [DataController::class, 'searchByNim']);

    // E. Cari by YMD
    Route::get('/search/ymd',  [DataController::class, 'searchByYmd']);

    // Preview semua data eksternal
    Route::get('/data/all',    [DataController::class, 'all']);
});