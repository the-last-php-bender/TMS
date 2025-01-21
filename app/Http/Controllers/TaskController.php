<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Interfaces\TaskInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class TaskController extends Controller
{
    protected TaskInterface $taskInterface;

    public function __construct(TaskInterface $taskInterface)
    {
        $this->taskInterface = $taskInterface;
    }

    public function index(): JsonResponse
    {
        try {
            $tasks = $this->taskInterface->getAllTasks();
            return response()->json($tasks, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(TaskRequest $request): JsonResponse
    {
        try {
            return response()->json($this->taskInterface->createTask($request->validated()), 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show(string $id): JsonResponse
    {
        try {
            $task = $this->taskInterface->getTaskById($id);
            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }
            return response()->json($task, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        if (empty($id)) {
            return response()->json(['error' => 'Task ID is required'], 400);
        }

        try {
            $task = $this->taskInterface->getTaskById($id);
            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            $updatedTask = $this->taskInterface->updateTask($id, $request->all());
            return response()->json($updatedTask, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function destroy(string $id): JsonResponse
    {
        try {
            if (empty($id)) {
                return response()->json(['error' => 'Task ID is required'], 400);
            }

            $deleted = $this->taskInterface->deleteTask($id);
            if (!$deleted) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            return response()->json(['message' => 'Task deleted successfully'], 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
