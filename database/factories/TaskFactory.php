<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Especifica el modelo asociado con la fábrica.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define los valores por defecto para una tarea.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Crea y asigna un usuario a la tarea
            'title' => $this->faker->sentence(), // Genera un título aleatorio
            'description' => $this->faker->paragraph(), // Genera una descripción aleatoria
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']), // Estado aleatorio
        ];
    }
}
