<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    private const CACHE_TEMPLATE = 'C%s_P%d_S%d';

    /**
     * Verify if there are saved cache for informed $countyCode, $pageNumber and $pageSize
     * @param string $countyCode
     * @param int $pageNumber
     * @param int $pageSize
     * @return array|null
     */
    public static function verifyCacheForCountyCode(string $countyCode, int $pageNumber, int $pageSize): ?array
    {
        $cacheName = self::buildCacheName($countyCode, $pageNumber, $pageSize);
        $cacheResult = Cache::get($cacheName);

        if (!$cacheResult) {
            return null;
        }

        return $cacheResult;
    }

    /**
     * Save the $data in cache for the informed $countyCode, $pageNumber and $pageSize
     * @param string $countyCode
     * @param int $pageNumber
     * @param int $pageSize
     * @param array $data
     * @return void
     */
    public static function saveCache(string $countyCode, int $pageNumber, int $pageSize, array $data): void
    {
        $cacheName = self::buildCacheName($countyCode, $pageNumber, $pageSize);
        Cache::put($cacheName, $data, now()->addMinutes(10));
    }

    /**
     * Returns the cache name that follows the format: "C<county-code>_P<page-number>_S<page-size>"
     * @param string $countyCode
     * @param int $pageNumber
     * @param int $pageSize
     * @return string
     */
    private static function buildCacheName(string $countyCode, int $pageNumber, int $pageSize): string
    {
        return sprintf(self::CACHE_TEMPLATE, $countyCode, $pageNumber, $pageSize);
    }
}
