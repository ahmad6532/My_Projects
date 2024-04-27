<?php

namespace App\Http\Middleware;

use App\Enums\User\UserRoleEnum;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RiderCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role === UserRoleEnum::Rider->value) {
            return $next($request);
        } else {
            return response()->json(['response' => ['status' => false, 'data' => 'Sorry! You cannot Access this Path.']], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
