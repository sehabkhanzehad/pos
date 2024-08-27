<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function generateToken($email, $id, $expTime)
    {
        $key = env('JWT_KEY');
        $data = [
            'iss' => 'jwt-token',
            'iat' => time(),
            'exp' => time() + $expTime,
            'email' => $email,
            'id' => $id
        ];
        return JWT::encode($data, $key, 'HS256');
    }

    public static function decodeToken($token)
    {

        try {
            if ($token == null) {
                return "Unauthorized";
            } else {
                $key = env('JWT_KEY');
                $decode = JWT::decode($token, new Key($key, 'HS256'));
                return $decode;
            }
        } catch (\Exception $e) {
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => $e->getMessage(),
            // ]);

            return "Unauthorized";
        }
    }
}
