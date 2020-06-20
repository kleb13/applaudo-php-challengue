<?php

namespace App\Providers;

use App\Contracts\MovieStore;
use App\Models\Movie;
use App\Observers\MovieObserver;
use App\Services\MovieStoreService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MovieStore::class,MovieStoreService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
        Movie::observe(MovieObserver::class);
    }
}
