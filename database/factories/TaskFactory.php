<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Asocia la tarea con un usuario
            'title' => $this->faker->sentence(), // Genera un título aleatorio
            'description' => $this->faker->paragraph(), // Genera una descripción aleatoria
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']), // Estado aleatorio
        ];
    }
}