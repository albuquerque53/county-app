<?php

namespace App\Http\Controllers;

use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GetCountyInfoController extends Controller
{
    private const CACHE_TEMPLATE = 'C%s_P%d_S%d';
    public function __construct(private readonly AbstractCountyService $service)
    {
        //
    }

    /**
     * Will search information about country_code informed
     * in defined external API.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $code = strtoupper($request->route('code'));

        $pageNumber = $request->query('page_number') ?? 1;
        $pageSize = $request->query('page_size') ?? 100;

        $cacheName = $this->getCacheName($code, $pageNumber, $pageSize);

        $cacheResult = Cache::get($cacheName);

        if ($cacheResult) {
            return $this->sendJsonResponse($cacheResult);
        }

        $data = $this->service->getInfoByCountyCode($code, $pageNumber, $pageSize);

        Cache::put($cacheName, $data, now()->addMinutes(10));

        return $this->sendJsonResponse($data);
    }

    private function getCacheName(string $countyCode, int $pageNumber, int $pageSize): string
    {
        return sprintf(self::CACHE_TEMPLATE, $countyCode, $pageNumber, $pageSize);
    }
}
