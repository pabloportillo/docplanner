<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define los valores por defecto para el modelo de usuario.
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(), // Genera un nombre aleatorio
            'email' => $this->faker->unique()->safeEmail(), // Genera un email único
            'password' => bcrypt('password'), // Contraseña encriptada por defecto
        ];
    }
}
