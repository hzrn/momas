<?php
// In App\Http\Middleware\SetLocale.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if ($locale = session('app_locale')) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
