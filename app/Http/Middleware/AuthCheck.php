<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $logInToken = $request->cookie('logInToken');
        $result = JWTToken::decodeToken($logInToken);
        if($result == "Unauthorized"){
            return redirect()->route('user.login')->with('error', 'Unauthorized');
        }else{
            $request->headers->set('email', $result->email);
            $request->headers->set('id', $result->id);
            return $next($request);
        }
    }
}
