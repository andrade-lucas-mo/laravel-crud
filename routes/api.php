<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('despesa', DespesaController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
