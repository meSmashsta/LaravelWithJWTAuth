<?php

namespace App\Http\Middleware;

use App\JWT;
use Closure;
use Throwable;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    private JWT $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
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
        try {
            $token = $this->jwt->parseToken($request->header('Authorization'));
            if (! $this->jwt->isValid($token)) {
                return response()->json(['message' => 'Access Denied'], 403);
            }
        } catch(Throwable $_) {
            return response()->json(['message' => 'Access Denied'], 403);
        }
        
        return $next($request);
    }

}
