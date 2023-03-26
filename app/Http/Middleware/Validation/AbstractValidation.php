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
     * @param ...$params
     * @return array
     */
    protected function getRouteParams(Request $request, array $params): array
    {
        $routeParams = [];

        foreach ($params as $paramName) {
            $routeParams[] = [$paramName => strtoupper($request->route($paramName))];
        }

        return $routeParams;
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

        throw new DomainException($this->buildErrorResponse($validation));
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
