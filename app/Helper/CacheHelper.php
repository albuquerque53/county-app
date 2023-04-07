<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    private const CACHE_TEMPLATE = 'C%s_P%d_S%d';

    public static function verifyCacheForCountyCode(string $countyCode, int $pageNumber, int $pageSize): ?array
    {
        $cacheName = self::buildCacheName($countyCode, $pageNumber, $pageSize);
        $cacheResult = Cache::get($cacheName);

        if (!$cacheResult) {
            return null;
        }

        return $cacheResult;
    }

    public static function saveCache(string $countyCode, int $pageNumber, int $pageSize, array $data): void
    {
        $cacheName = self::buildCacheName($countyCode, $pageNumber, $pageSize);
        Cache::put($cacheName, $data, now()->addMinutes(10));
    }

    private static function buildCacheName(string $countyCode, int $pageNumber, int $pageSize): string
    {
        return sprintf(self::CACHE_TEMPLATE, $countyCode, $pageNumber, $pageSize);
    }
}
