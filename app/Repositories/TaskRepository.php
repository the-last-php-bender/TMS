<?php

namespace App\Repositories;

use App\Interfaces\TaskInterface;
use App\Models\Task;
class TaskRepository implements TaskInterface
{
    public function getAllTasks()
    {
        return Task::all();
    }

    public function getTaskById($id)
    {
        return Task::findOrFail($id);
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function updateTask($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }


    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        return $task->delete();
    }
}
