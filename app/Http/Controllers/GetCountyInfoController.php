<?php

namespace App\Http\Controllers;

use App\Helper\CacheHelper;
use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $cacheResult = CacheHelper::verifyCacheForCountyCode($code, $pageNumber, $pageSize);

        if ($cacheResult) {
            return $this->sendJsonResponse($cacheResult);
        }

        $data = $this->service->getInfoByCountyCode($code, $pageNumber, $pageSize);

        CacheHelper::saveCache($code, $pageNumber, $pageSize, $data);

        return $this->sendJsonResponse($data);
    }
}
