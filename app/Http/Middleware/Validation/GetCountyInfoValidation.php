<?php

namespace App\Http\Middleware\Validation;

use App\Enums\CountyCodeEnum;
use App\Exceptions\DomainException;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\Rules\Enum;
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
     * @throws DomainException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestParameters = array_merge($this->getRouteParams($request, ['code']), $request->all());

        $validation = FacadeValidator::make(
            $requestParameters,
            [
                'code' => [new Enum(CountyCodeEnum::class)],
                'page_number' => 'integer|max:500',
                'page_size' => 'integer|max:2000'
            ],
            [
                'code' => [
                    'The county code informed is not valid',
                    'Allowed values are: ' . $this->getAllowedCountyCodes(),
                ]
            ]
        );

        $this->handleValidation($validation);

        return $next($request);
    }

    private function getAllowedCountyCodes(): string
    {
        $countyCodes = json_encode(CountyCodeEnum::cases());

        return str_replace(['\\', '"'], '', $countyCodes);
    }
}
