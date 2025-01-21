<?php

namespace App\Http\Controllers;

use App\Interfaces\TaskInterface;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TaskController extends Controller
{
    private $taskInterface;

    public function __construct(TaskInterface $taskInterface)
    {
        $this->taskInterface = $taskInterface;
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json($this->taskInterface->getAllTasks());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(TaskRequest $request): JsonResponse

    {
        try {
            return response()->json($this->taskInterface->createTask($request->validated()), 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            if (empty($id)) {
                return response()->json(['error' => 'Task ID is required'], 400);
            }
            return response()->json($this->taskInterface->getTaskById($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            if (empty($id)) {
                return response()->json(['error' => 'Task ID is required'], 400);
            }
            $task = $this->taskInterface->getTaskById($id);
            $task->update($request->only(['title', 'description', 'status']));

            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
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
            $this->taskInterface->deleteTask($id);
            return response()->json(['message' => 'Task deleted successfully'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
