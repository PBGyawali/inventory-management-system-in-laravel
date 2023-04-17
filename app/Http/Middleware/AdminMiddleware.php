<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;
class AdminMiddleware
{


     /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
            if (!auth()->user()->is_admin()) {
                if ($request->ajax()) {
                    return response()->json(['error'=>
                    __('message.error.admin_action')]);
                }
                return back()->with('error',__('message.error.admin_view'));
            }

        return $next($request);
    }
}
