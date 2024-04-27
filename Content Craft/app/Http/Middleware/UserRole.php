<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use App\Models\Role;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var App\Models\User */
        $user  = Auth::user();
        $requestType = $request->is('api*') ? 'api' : 'web';
        if ($requestType === 'api') {
        if ($user->hasRole(UserRoleEnum::USER)) {
                return $next($request);
            }
            return response()->json(['response' => ['status' => false, 'message' => "Only User Can Access this Path"]], JsonResponse::HTTP_UNAUTHORIZED);
        }
        if ($user->hasRole(UserRoleEnum::USER)) {
            return $next($request);
        }
        return back();
    }
}
