<?php

namespace libs\Response;

/**
 * Response
 * 
 * @package libs\Response
 */
class Response
{
    /**
     * Response
     *
     * @access private
     * @var void
     */
    private $response;

    /**
     * Status code for response
     *
     * @access private
     * @var int
     */
    private $statusCode;

    public function __construct($response, int $statusCode)
    {
        $this->response = $response;
        $this->statusCode = $statusCode;    
    }

    /**
     * Return json string with status code
     *
     * @return string
     */
    public function __toString()
    {
        http_response_code($this->statusCode);
        return json_encode(["response" => $this->response]);
    }
}