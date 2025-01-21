<?php

namespace Tests\Unit;

use App\Http\Controllers\TaskController;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class TaskControllerUnitTest extends TestCase
{
    protected TaskController $taskController;
    protected $taskInterfaceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskInterfaceMock = Mockery::mock(TaskInterface::class);
        $this->taskController = new TaskController($this->taskInterfaceMock);
    }

    public function test_index()
    {
        $tasks = Task::factory()->count(3)->make();
        $this->taskInterfaceMock
            ->shouldReceive('getAllTasks')
            ->once()
            ->andReturn($tasks);
        $response = $this->taskController->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(3, $response->getData());
    }

    public function test_index_no_tasks()
    {
        $this->taskInterfaceMock
            ->shouldReceive('getAllTasks')
            ->once()
            ->andReturn([]);
        $response = $this->taskController->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, $response->getData());
    }

    public function test_store()
    {
        $taskData = ['title' => 'Test Task', 'description' => 'Test Task Description', 'status' => 'pending'];
        $taskRequestMock = Mockery::mock(\App\Http\Requests\TaskRequest::class);
        $taskRequestMock->shouldReceive('validated')->andReturn($taskData);
        $this->taskInterfaceMock
            ->shouldReceive('createTask')
            ->once()
            ->with($taskData)
            ->andReturn(new Task($taskData));
        $response = $this->taskController->store($taskRequestMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_show()
    {
        $taskId = 1;
        $task = new Task(['id' => $taskId, 'title' => 'Test Task', 'description' => 'Test Task Description', 'status' => 'pending']);
        $this->taskInterfaceMock
            ->shouldReceive('getTaskById')
            ->once()
            ->with($taskId)
            ->andReturn($task);
        $response = $this->taskController->show($taskId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_show_task_not_found()
    {
        $taskId = 1;
        $this->taskInterfaceMock
            ->shouldReceive('getTaskById')
            ->once()
            ->with($taskId)
            ->andReturn(null);
        $response = $this->taskController->show($taskId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Task not found', $response->getData()->error);
    }

    public function test_update()
    {
        $taskId = 1;
        $taskData = ['title' => 'Updated Task', 'description' => 'Updated Description', 'status' => 'completed'];
        $task = new Task(['id' => $taskId, 'title' => 'Old Task', 'description' => 'Old Description', 'status' => 'pending']);
        $this->taskInterfaceMock
            ->shouldReceive('getTaskById')
            ->once()
            ->with($taskId)
            ->andReturn($task);
        $this->taskInterfaceMock
            ->shouldReceive('updateTask')
            ->once()
            ->with($taskId, $taskData)
            ->andReturn(new Task($taskData));
        $response = $this->taskController->update(new \Illuminate\Http\Request($taskData), $taskId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_update_task_not_found()
    {
        $taskId = 1;
        $taskData = ['title' => 'Updated Task', 'description' => 'Updated Description', 'status' => 'completed'];
        $this->taskInterfaceMock
            ->shouldReceive('getTaskById')
            ->once()
            ->with($taskId)
            ->andReturn(null);
        $response = $this->taskController->update(new \Illuminate\Http\Request($taskData), $taskId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Task not found', $response->getData()->error);
    }

    public function test_update_missing_task_id()
    {
        $response = $this->taskController->update(new \Illuminate\Http\Request([]), '');
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Task ID is required', $response->getData()->error);
    }

    public function test_destroy()
    {
        $taskId = 1;
        $this->taskInterfaceMock
            ->shouldReceive('deleteTask')
            ->once()
            ->with($taskId)
            ->andReturn(true);
        $response = $this->taskController->destroy($taskId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function test_destroy_task_not_found()
    {
        $taskId = 1;
        $this->taskInterfaceMock
            ->shouldReceive('deleteTask')
            ->once()
            ->with($taskId)
            ->andReturn(false);
        $response = $this->taskController->destroy($taskId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Task not found', $response->getData()->error);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
