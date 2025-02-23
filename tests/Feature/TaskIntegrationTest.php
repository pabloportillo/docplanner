<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Laravel\Passport\Passport;

class TaskIntegrationTest extends TestCase
{
    public function test_create_task_via_api()
    {
        // Autenticar un usuario
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Datos de la tarea
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'pending',
        ];

        // Hacer una solicitud POST a la API
        $response = $this->postJson('/api/tasks', $taskData);

        // Verificar que la respuesta es correcta
        $response->assertStatus(201)
                 ->assertJson(['title' => 'Test Task']);

        // Verificar que la tarea se guardó en la base de datos
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id,
        ]);
    }

    public function test_get_user_tasks_via_api()
    {
        // Autenticar un usuario
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Crear tareas asociadas al usuario
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        // Hacer una solicitud GET a la API
        $response = $this->getJson('/api/tasks');

        // Verificar que la respuesta es correcta
        $response->assertStatus(200)
                 ->assertJsonCount(3); // Verificar que se devuelven 3 tareas
    }

    public function test_update_task_via_api()
    {
        // Autenticar un usuario
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Datos de actualización
        $updateData = [
            'title' => 'Updated Task Title',
            'status' => 'completed',
        ];

        // Hacer una solicitud PUT a la API
        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        // Verificar que la respuesta es correcta
        $response->assertStatus(200)
                 ->assertJson(['title' => 'Updated Task Title']);

        // Verificar que la tarea se actualizó en la base de datos
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'completed',
        ]);
    }

    public function test_delete_task_via_api()
    {
        // Autenticar un usuario
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Hacer una solicitud DELETE a la API
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // Verificar que la respuesta es correcta
        $response->assertStatus(204);

        // Verificar que la tarea ya no existe en la base de datos
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}