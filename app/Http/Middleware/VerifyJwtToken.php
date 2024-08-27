<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        $passResetToken = $request->cookie('passResetToken');
        // $passResetToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3QtdG9rZW4iLCJpYXQiOjE3MjQwNTQ5MDgsImV4cCI6MTcyNDA1NTgwOCwiZW1haWwiOiJzZWhhYmtoYW56ZWhhZEB5YWhvby5jb20iLCJpZCI6MX0.tgzkspOttKRhXgP1OLLguzC33Zj-Dz2pXcOQ4vfcCBAx";
        // return response ()->json([
        //     "token" => $passResetToken
        // ]);

        $result = JWTToken::decodeToken($passResetToken);

        if ($result == "Unauthorized") {

            // redirect login page
            return redirect()->route('user.login');
            // return response()->json([
            //     "result " => $result,
            // ]);
        } else {

            // return response()->json([
            //     "email" => $result->email,
            //     "id" => $result->id
            // ]);
            $request->headers->set('email', $result->email);
            $request->headers->set('id', $result->id);
            // $request->attributes->set('id', $result->id);
            return $next($request);


        // Continue the request handling
        // $response = $next($request);

        // // Add headers to the response
        // $response->headers->set('email', $result->email);
        // $response->headers->set('id', $result->id);

        // return $response;
        }
    }
}
