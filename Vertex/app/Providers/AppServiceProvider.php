<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Version_History;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('themeColor', Config::all());
            $view->with('setting', Setting::all());
            $view->with('version', Version_History::where('type', 'web')->orderBy('created_at','desc')->first());
        });
    }
}
