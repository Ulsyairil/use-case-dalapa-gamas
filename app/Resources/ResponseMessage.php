<?php

namespace App\Resources;

class ResponseMessage {
    protected static $message = [
        0   => "Success",
        1   => "Validation Error",
        2   => "Invalid Token or Expired",
        3   => "CSRF Token Mismatch",
        4   => "Missing Token",
        9   => "Unknown Error",
        10  => "User Not Found",
        11  => "User Already Exists",
        12  => "Invalid Password",
        13  => "User Not Active",
        20  => "Record Not Found",
        900 => "Server Not Responding", // Http Endpoint Error
        901 => "Server Return Error", // Internal Server Error
    ];

    public static function getMessage($code) {
        return self::$message[$code];
    }
}