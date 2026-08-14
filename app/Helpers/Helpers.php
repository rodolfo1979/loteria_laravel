<?php

namespace App\Helpers;

class Helpers
{
    public static function responseJSON($success = true, $message = "", $data = [], $code = 200)
    {
        return response()->json([
            "success" => $success,
            "message" => $message,
            "data" => $data
        ], $code, [], JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES);
    }

}
