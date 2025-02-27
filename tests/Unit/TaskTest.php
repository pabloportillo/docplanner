<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba la creación de una tarea.
     */
    public function test_task_creation()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();
    
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);
    
        // Verificar que la tarea se ha guardado en la base de datos
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $user->id,
            'title' => $task->title,
            'status' => $task->status,
        ]);
    
        // Comprobar que la relación con el usuario es correcta
        $this->assertEquals($user->id, $task->user_id);
    }

    /**
     * Prueba la actualización de una tarea.
     */
    public function test_task_update()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();
    
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);
    
        // Actualizar el estado de la tarea
        $task->update(['status' => 'completed']);
    
        // Verificar que la actualización se reflejó en la base de datos
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Prueba la eliminación de una tarea.
     */
    public function test_task_deletion()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();
    
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);
    
        // Eliminar la tarea
        $task->delete();
    
        // Verificar que la tarea ha sido eliminada de la base de datos
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
