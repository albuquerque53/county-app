<?php

namespace App\Http\Controllers;

use App\Enums\CountyCodeEnum;
use App\Repository\CountyRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetCountyInfoController extends Controller
{
    public function __construct(private readonly CountyRepositoryInterface $countyRepository)
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
        $code = CountyCodeEnum::from(strtoupper($request->route('code')));

        $pageNumber = $request->query('page_number') ?? 1;
        $pageSize = $request->query('page_size') ?? 100;

        $countyData = $this->countyRepository->findByCountyCode($code, $pageNumber, $pageSize);

        return response()->json($countyData);
    }
}
