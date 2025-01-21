<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TaskInterface;
use App\Repositories\TaskRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaskInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
