<?php
namespace App\Http\Middleware;

use Closure;
use App;
use Cookie;
class LanguageManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('locale')) {
            App::setLocale(Cookie::get('lang')?Cookie::get('lang'):session()->get('locale'));
        }

        return $next($request);
    }
}
