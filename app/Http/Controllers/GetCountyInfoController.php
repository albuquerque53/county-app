<?php

namespace App\Http\Controllers;

use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GetCountyInfoController extends Controller
{
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

        $cacheResult = Cache::get($code);

        if ($cacheResult) {
            return $this->sendJsonResponse($cacheResult);
        }

        $data = $this->service->getInfoByCountyCode($code);

        Cache::put($code, $data, now()->addMinutes(10));

        return $this->sendJsonResponse($data);
    }
}
