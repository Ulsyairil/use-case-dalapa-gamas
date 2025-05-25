<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SwaggerBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $username = config('app.custom.swagger_username');
        $password = config('app.custom.swagger_password');

        if ($request->getUser() !== $username || $request->getPassword() !== $password) {
            return response('Unauthorized.', 401, ['WWW-Authenticate' => 'Basic']);
        }

        return $next($request);
    }
}
