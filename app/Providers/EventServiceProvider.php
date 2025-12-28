<?php

namespace App\Providers;

use App\Events\Cache\Queries\GetModelByCriteria;
use App\Events\Cache\Queries\GetModelById;
use App\Events\Cache\Queries\GetModels;
use App\Events\Cache\Updates\ModelCreated;
use App\Events\Cache\Updates\ModelDeleted;
use App\Events\Cache\Updates\ModelUpdated;
use App\Listeners\Cache\DelRelatedCache;
use App\Listeners\Cache\StoreCache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
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
        Event::listen(GetModels::class, StoreCache::class);
        Event::listen(GetModelById::class, StoreCache::class);
        Event::listen(GetModelByCriteria::class, StoreCache::class);

        Event::listen(ModelCreated::class, DelRelatedCache::class);
        Event::listen(ModelCreated::class, StoreCache::class);

        Event::listen(ModelUpdated::class, DelRelatedCache::class);
        Event::listen(ModelUpdated::class, StoreCache::class);

        Event::listen(ModelDeleted::class, DelRelatedCache::class);
    }
}
