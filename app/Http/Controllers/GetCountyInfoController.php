<?php

namespace App\Http\Controllers;

use App\Enums\CountyCodeEnum;
use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
        $code = $request->route('code');

        $data = $this->service->getInfoByCountyCode($code);

        return response()->json($data);
    }
}
