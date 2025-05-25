<?php

namespace App\Http\Middleware;

use App\Models\AdminToken;
use App\Resources\CustomResponse;
use App\Resources\ResponseMessage;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $actorId = $request->input('actor_id'); // Admin ID
        $token   = $request->input('token');

        if (!$actorId || !$token) {
            return CustomResponse::createResponse([], 4, ResponseMessage::getMessage(4));
        }

        $adminToken = AdminToken::query()
            ->where('admin_id', $actorId)
            ->where('token', $token)
            ->first();

        if (!$adminToken || Carbon::now()->greaterThan($adminToken->expired_at)) {
            return CustomResponse::createResponse([], 2, ResponseMessage::getMessage(2));
        }

        return $next($request);
    }
}
