<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            //redirect if the user is already authenticated
            if (Auth::guard($guard)->check()) {
                if ($request->ajax()) {
                    return response()->json(array('redirect'=>RouteServiceProvider::HOME));
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
