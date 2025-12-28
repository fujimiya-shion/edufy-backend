<?php
namespace App\Services\Cache;

use Cache;
use DateInterval;
class CacheService {
    public function get(string $key, array | null $tags = null): mixed {
        return $tags ? Cache::tags($tags)->get($key) : Cache::get($key);
    }

    public function set(string $key, mixed $value, DateInterval | int | null $ttl = null, array | null $tags = null): bool {
        return $tags ? Cache::tags($tags)->put($key, $value, $ttl) : Cache::set($key, $value, $ttl);
    }

    public function forever(string $key, mixed $value, array | null $tags = null): bool {
        return $tags ? Cache::tags($tags)->put($key, $value) : Cache::forever($key, $value);
    }

    public function delete(string $key, array | null $tags = null): bool {
        return $tags ? Cache::tags($tags)->delete($key) : Cache::delete($key);
    }

    public function deleteCacheList(string $model, array | null $tags = null): void
    {
        $cache = $tags ? Cache::tags($tags) : Cache::class;
        if ($cache instanceof \Illuminate\Cache\TaggedCache) {
            $keys = $cache->getRedis()->keys("*:$model:*");
            foreach ($keys as $key) {
                $realKey = str_replace(config('const.cache.prefix'), '', $key);
                $realKey = preg_replace('/^[^:]+:/', '', $realKey);
                $realKeyArr = explode(':', $realKey);
                $deleteCache = true;
                if(count($realKeyArr) > 1 && is_numeric($realKeyArr[1]))
                    $deleteCache = false;
                if($deleteCache)
                    $cache->delete($realKey);
            }
        }
    }

    public function deleteBy(string $pattern, array | null $tags = null): int {
        $cache = $tags ? Cache::tags($tags) : Cache::class;
        $deletedCount = 0;

        if ($cache instanceof \Illuminate\Cache\TaggedCache) {
            $keys = $cache->getRedis()->keys("{$pattern}:*");
            
            foreach ($keys as $key) {
                if ($cache->forget($key)) {
                    $deletedCount++;
                }
            }
        }
        return $deletedCount;
    }
}