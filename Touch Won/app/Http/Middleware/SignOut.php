<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SignOut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
      if (!session()->has('data') && url('terms-&-conditions')==$request->url())
        {
            return response()->view('terms_&_conditions');
        }
      elseif(!session()->has('data') || url()==$request->url())
      {
          return redirect('signin');
      }

        return $next($request);
    }
}
