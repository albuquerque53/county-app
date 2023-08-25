<?php

namespace App\Repository;

use App\Enums\CountyCodeEnum;
use App\Helper\CacheHelper;
use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Support\Collection;

class CountyRepository implements CountyRepositoryInterface
{
    public function __construct(private readonly AbstractCountyService $countyService)
    {
        //
    }

    public function findByCountyCode(CountyCodeEnum $countyCode, int $pageNumber, int $pageSize): Collection
    {
        if ($cacheResult = CacheHelper::verifyCacheForCountyCode($countyCode, $pageNumber, $pageSize)) {
            return collect($cacheResult);
        }

        $countyData = $this
            ->countyService
            ->getInfoByCountyCode($countyCode->value, $pageNumber, $pageSize);

        CacheHelper::saveCache($countyCode, $pageNumber, $pageSize, $countyData);

        return collect($countyData);
    }
}

