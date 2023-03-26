<?php

namespace App\Http\Middleware;

use App\Exceptions\DomainException;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler
{
    /**
     * Try to handle request or returns an error JSON Response
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response =  $next($request);

        if (!$response->exception) {
            return $response;
        }

        if ($response->exception instanceof DomainException) {
            $responseExceptionCode = $response->exception->getCode();
            $status = $responseExceptionCode == 0 ? 400 : $responseExceptionCode;

            return new JsonResponse(data: ['error' => $response->exception->getMessage()], status: $status ?? 400);
        }

        Log::error($response->exception->getMessage());
        return new JsonResponse(data: ['error' => 'An unexpected error ocurred'], status: 500);
    }
}
