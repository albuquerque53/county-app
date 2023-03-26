<?php

namespace App\Http\Middleware\Validation;

use App\Enums\CountyCodeEnum;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class GetCountyInfoValidation extends AbstractValidation
{
    /**
     * Define the rules and validate request parameters
     * according to them.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validation = FacadeValidator::make(
            $this->getRouteParams($request, ['code']),
            [
                'code' => [new Enum(CountyCodeEnum::class)],
            ],
            [
                'code' => [
                    'error' => [
                        'The county code informed is not valid',
                        'Allowed values: ' . json_encode(CountyCodeEnum::cases()),
                    ],
                ]
            ]
        );

        $this->handleValidation($validation);

        return $next($request);
    }
}
