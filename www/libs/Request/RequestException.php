<?php

namespace libs\Request;

use Exception;

class RequestException extends Exception
{
    public function __construct($message, $statusCode, $code = 0, Exception $previous = null) {
        http_response_code($statusCode);
        $message = json_encode(["response" => $message]);
    
        parent::__construct($message, $code, $previous);
    }
}