<?php

namespace App\Listeners\Cache;

use App\Interfaces\Cache\Events\ICacheEvent;
use App\Services\Cache\CacheService;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class StoreCache
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
        $this->cacheService->forever($event->getCacheKey(), $event->getData(), [class_basename($event->getModel())]);
    }
}
