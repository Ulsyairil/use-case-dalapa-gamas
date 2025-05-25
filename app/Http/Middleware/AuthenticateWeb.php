<?php

namespace App\Http\Middleware;

use App\Models\AdminToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class AuthenticateWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  $mode  Optional mode (e.g., 'guest')
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $mode = null)
    {
        $actorId   = Session::get('actor_id');
        $cacheKey  = 'admin_' . $actorId;
        $adminData = $actorId ? Cache::get($cacheKey) : null;

        if ($mode === 'guest') {
            if ($actorId && $adminData) {
                return redirect()->route('ticket.view');
            }
            return $next($request);
        }

        if (!$actorId || !$adminData) {
            Session::forget('actor_id');
            Cache::forget($cacheKey);
            return redirect()->route('login.view');
        }

        $checkTokenExist = AdminToken::query()
            ->where('admin_id', $actorId)
            ->where('token', $adminData['token'])
            ->exists();

        if (!$checkTokenExist) {
            Session::forget('actor_id');
            Cache::forget($cacheKey);
            return redirect()->route('login.view');
        }

        // Make admin data available downstream
        $request->attributes->set('actor_id', $actorId);
        $request->attributes->set('admin_data', $adminData);

        return $next($request);
    }
}
