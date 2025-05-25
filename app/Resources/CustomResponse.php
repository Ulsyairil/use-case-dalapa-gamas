<?php

namespace App\Resources;

class CustomResponse {
    public static function createResponse($data = [], Int $code = 0, String $msg = '', $cookie = ''){
        $response = [
            "code"    => $code,
            "message" => $msg,
            "data"    => $data,
        ];

        $withCookie = cookie('_em', '', 0);

        if ($cookie) {
            $withCookie = $cookie;
        }

        return response()->json($response)->withCookie($withCookie);
    }
}