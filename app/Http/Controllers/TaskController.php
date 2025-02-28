<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Muestra la lista de tareas del usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Obtener las tareas asociadas al usuario autenticado
        $tasks = Auth::user()->tasks;
        return response()->json($tasks, 200);
    }

    /**
     * Almacena una nueva tarea en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
        ]);

        // Crear la tarea asociándola al usuario autenticado
        $task = Auth::user()->tasks()->create($request->all());

        return response()->json($task, 201);
    }

    /**
     * Muestra una tarea específica.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = Task::where('id', $id)
                   ->where('user_id', Auth::id())
                   ->firstOrFail();

        return response()->json($task, 200);
    }

    /**
     * Actualiza una tarea existente.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Busca la tarea por ID y verifica que pertenezca al usuario autenticado
            $task = Task::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail();
    
            // Valida los datos de entrada
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'status' => 'sometimes|in:pending,in_progress,completed',
            ]);
    
            // Actualiza la tarea
            $task->update($request->all());
    
            return response()->json($task, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tarea no encontrada o no tienes permisos para modificarla.',
            ], 404);
        }
    }

    /**
     * Elimina una tarea de la base de datos.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Busca la tarea por ID y verifica que pertenezca al usuario autenticado
            $task = Task::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail();
    
            // Elimina la tarea
            $task->delete();
    
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tarea no encontrada o no tienes permisos para eliminarla.',
            ], 404);
        }
    }
}