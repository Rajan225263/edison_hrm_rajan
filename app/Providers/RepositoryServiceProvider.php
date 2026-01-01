<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// ==== Contracts ====
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\SkillRepositoryInterface;

// ==== Implementations ====
use App\Repositories\UserRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\SkillRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
          $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
    }

    public function boot()
    {
        //
    }
}
