<?php

namespace App\Repository;

use App\Enums\CountyCodeEnum;
use Illuminate\Support\Collection;

interface CountyRepositoryInterface
{
    /**
     * Should return county code data provided
     * by external provider.
     *
     * @param CountyCodeEnum $countyCode
     * @param int $pageNumber
     * @param int $pageSize
     * @return Collection
     */
    public function findByCountyCode(CountyCodeEnum $countyCode, int $pageNumber, int $pageSize): Collection;
}
