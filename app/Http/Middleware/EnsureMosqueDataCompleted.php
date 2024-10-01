<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMosqueDataCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->mosque == null) {
            flash('Mosque data is not completed, please fill the mosque form first')
            ->error();
            return redirect()->route('mosque.create');
        }
        return $next($request);
    }
}
