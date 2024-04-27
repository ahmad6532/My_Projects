<?php

namespace App\Providers;

use App\Repositories\Interfaces\Customer\CustomerRepositoryInterface;
use App\Repositories\Interfaces\Feedback\FeedbackRepositoryInterface;
use App\Repositories\Interfaces\Order\OrderRepositoryInterface;
use App\Repositories\Interfaces\Rider\RiderRepositoryInterface;
use App\Repositories\Repositories\Customer\CustomerRepository;
use App\Repositories\Repositories\Feedback\FeedbackRepository;
use App\Repositories\Repositories\Order\OrderRepository;
use App\Repositories\Repositories\Rider\RiderRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
