<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class TaskTest extends TestCase
{
    public function test_task_creation()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();
    
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);
    
        // Verificar que la tarea se creó correctamente
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $user->id,
            'title' => $task->title,
            'status' => $task->status,
        ]);
    
        // Verificar la relación con el usuario
        $this->assertEquals($user->id, $task->user_id);
    }

    public function test_task_update()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();
    
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);
    
        // Actualizar la tarea
        $task->update(['status' => 'completed']);
    
        // Verificar que la tarea se actualizó correctamente
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }
    public function test_task_deletion()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();
    
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $user->id]);
    
        // Eliminar la tarea
        $task->delete();
    
        // Verificar que la tarea ya no existe en la base de datos
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
    
}