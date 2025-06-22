<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
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
        if ($user->hasRole(UserRoleEnum::ADMIN)) {
            return $next($request);
        }
        return back();
    }
}
