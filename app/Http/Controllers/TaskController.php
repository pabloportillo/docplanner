<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        // Obtener las tareas del usuario autenticado
        $user = Auth::user();
        return $user->tasks;
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
        ]);

        // Crear la tarea y asociarla al usuario autenticado
        $user = Auth::user();
        $task = $user->tasks()->create($request->all());

        return response()->json($task, 201);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);
    
        // Asegurarse de que el usuario autenticado sea el dueño de la tarea
        $user = Auth::user();
        if ($task->user_id === $user->id) {
            $task->update($request->all());
            return response()->json($task, 200);
        }
    
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        // Asegurarse de que el usuario autenticado sea el dueño de la tarea
        $user = Auth::user();
        if ($task->user_id === $user->id) {
            $task->delete();
            return response()->json(null, 204);
        }
    
        return response()->json(['error' => 'Unauthorized'], 403);
    }

}