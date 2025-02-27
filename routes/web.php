<?php

use Illuminate\Support\Facades\Route;

// Ruta para servir el frontend
Route::get('/{any}', function () {
    return file_get_contents(public_path('task.html'));
})->where('any', '^(?!api\/).*$');