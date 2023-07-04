<?php

namespace App\Helper;

/**
 * Return standardise data format
 * @param array $data
 * @param int $httpCode
 */
class Response
{
    public function sendResponse(array|string $data = [], int $httpCode = 200)
    {
        return [
            "code" => $httpCode,
            "success" => (200 >= $httpCode && $httpCode < 300),
            "data" => $data
        ];
    }
}
