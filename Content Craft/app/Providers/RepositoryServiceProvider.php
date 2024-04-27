<?php

namespace App\Providers;

use App\Repositories\Interfaces\AdminInterface;
use App\Repositories\Interfaces\ArticleInterface;
use App\Repositories\Interfaces\ManagerInterface;
use App\Repositories\Interfaces\PlanInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Repositories\AdminRepository;
use App\Repositories\Repositories\ArticleRepository;
use App\Repositories\Repositories\ManagerRepository;
use App\Repositories\Repositories\PlanRepository;
use App\Repositories\Repositories\UserRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
 
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(ArticleInterface::class, ArticleRepository::class);
        $this->app->bind(PlanInterface::class, PlanRepository::class);
        $this->app->bind(ManagerInterface::class, ManagerRepository::class);
        $this->app->bind(AdminInterface::class, AdminRepository::class);
        
    }
}
