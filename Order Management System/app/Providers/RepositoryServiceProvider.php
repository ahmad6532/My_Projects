<?php

namespace App\Providers;

use App\Repositories\Interfaces\Customer\CustomerInterface;
use App\Repositories\Interfaces\Feedback\FeedbackInterface;
use App\Repositories\Interfaces\Order\OrderInterface;
use App\Repositories\Interfaces\Rider\RiderInterface;
use App\Repositories\Repositories\Customer\CustomerRepository;
use App\Repositories\Repositories\Feedback\FeedbackRepository;
use App\Repositories\Repositories\Order\OrderRepository;
use App\Repositories\Repositories\Rider\RiderRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
        $this->app->bind(RiderInterface::class, RiderRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(FeedbackInterface::class, FeedbackRepository::class);
    
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
