<?php

namespace App\Listeners\Cache;

use App\Interfaces\Cache\Events\ICacheEvent;
use App\Interfaces\Cache\ICacheService;
use App\Services\Cache\CacheService;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class DelRelatedCache
{
    protected CacheService $cacheService;
    public function __construct(CacheService $cacheService) {
        $this->cacheService = $cacheService;
    }
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $model = $event->getModel();
        if($model instanceof Model) {
            $this->cacheService->deleteCacheList(class_basename($model), [class_basename($model)]);
        }
    }
}
