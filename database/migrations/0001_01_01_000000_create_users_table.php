<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para crear la tabla de usuarios.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); 
            $table->string('password'); 
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración, eliminando la tabla de usuarios.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
