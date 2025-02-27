<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum; // Asegúrate de importar Sanctum
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba la creación de una tarea a través de la API.
     */
    public function test_create_task_via_api()
    {
        // Autenticar usuario usando Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user); // Cambiado a Sanctum

        // Datos de la tarea a crear
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'pending',
        ];

        // Enviar petición POST a la API
        $response = $this->postJson('/api/tasks', $taskData);

        // Verificar que la respuesta es 201 (creado) y contiene el título correcto
        $response->assertStatus(201)
                 ->assertJson(['title' => 'Test Task']);

        // Comprobar que la tarea se ha guardado en la base de datos
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Prueba la obtención de todas las tareas del usuario autenticado.
     */
    public function test_get_user_tasks_via_api()
    {
        // Autenticar usuario usando Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user); // Cambiado a Sanctum

        // Crear 3 tareas asociadas al usuario
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        // Enviar petición GET para obtener las tareas del usuario
        $response = $this->getJson('/api/tasks');

        // Verificar que la respuesta es 200 y devuelve 3 tareas
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /**
     * Prueba la actualización de una tarea existente mediante la API.
     */
    public function test_update_task_via_api()
    {
        // Autenticar usuario usando Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user); // Cambiado a Sanctum

        // Crear una tarea de prueba asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Datos para actualizar la tarea
        $updateData = [
            'title' => 'Updated Task Title',
            'status' => 'completed',
        ];

        // Enviar petición PUT para actualizar la tarea
        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        // Verificar que la respuesta es 200 y los datos se han actualizado
        $response->assertStatus(200)
                 ->assertJson(['title' => 'Updated Task Title']);

        // Comprobar que los cambios están en la base de datos
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'completed',
        ]);
    }

    /**
     * Prueba la eliminación de una tarea mediante la API.
     */
    public function test_delete_task_via_api()
    {
        // Autenticar usuario usando Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user); // Cambiado a Sanctum

        // Crear una tarea de prueba asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Enviar petición DELETE para eliminar la tarea
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // Verificar que la respuesta es 204 (sin contenido)
        $response->assertStatus(204);

        // Comprobar que la tarea ha sido eliminada de la base de datos
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}