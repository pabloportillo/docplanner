<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

// Grupo de rutas relacionadas con la autenticación
Route::prefix('auth')->group(function () {
    // Ruta para registrar un nuevo usuario
    Route::post('/register', [AuthController::class, 'register']);

    // Ruta para iniciar sesión y obtener un token de acceso
    Route::post('/login', [AuthController::class, 'login']);

    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Grupo de rutas protegidas por autenticación con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para obtener la información del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD de tareas
    Route::apiResource('tasks', TaskController::class);
});
