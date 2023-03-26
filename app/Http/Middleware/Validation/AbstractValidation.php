<?php

namespace App\Http\Middleware\Validation;

use App\Exceptions\DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

abstract class AbstractValidation
{
    /**
     * Will return an array with the $paramName => $paramValue
     *
     * @param Request $request
     * @param array $params
     * @return array
     */
    protected function getRouteParams(Request $request, array $params): array
    {
        $parsedParams = [];

        foreach ($params as $paramName) {
            $parsedParams = array_merge($parsedParams, [$paramName => strtoupper($request->route($paramName))]);
        }

        return $parsedParams;
    }

    /**
     * Verify if $validation failed...
     *  if true: Will throw an ValidationException with defined message
     *  if false: Returns
     *
     * @param Validator $validation
     * @return void
     * @throws DomainException
     */
    protected function handleValidation(Validator $validation): void
    {
        if (!$validation->fails()) {
            return;
        }

        throw new DomainException($validation->errors());
    }

    /**
     * Returns an object of JsonResponse with validation errors
     * and status code 422
     *
     * @param Validator $validation
     * @return JsonResponse
     */
    private function buildErrorResponse(Validator $validation): JsonResponse
    {
        return new JsonResponse(data: $validation->errors(), status: 422);
    }
}
