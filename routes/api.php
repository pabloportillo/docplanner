<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Rutas de API
|--------------------------------------------------------------------------
|
| Definición de las rutas para la API del proyecto. Se organizan en grupos
| según su propósito, incluyendo autenticación y operaciones protegidas.
|
*/

// Grupo de rutas relacionadas con la autenticación
Route::prefix('auth')->group(function () {
    // Registro de usuario
    Route::post('/register', [AuthController::class, 'register']);

    // Inicio de sesión
    Route::post('/login', [AuthController::class, 'login']);

    // Cierre de sesión (requiere autenticación)
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

// Grupo de rutas protegidas que requieren autenticación
Route::middleware('auth:api')->group(function () {
    // Obtiene los datos del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD de tareas utilizando un controlador de recursos
    Route::apiResource('tasks', TaskController::class);
});
