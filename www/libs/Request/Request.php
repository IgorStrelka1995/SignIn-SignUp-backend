<?php

namespace libs\Request;

use Exception;

/**
 * Request
 * 
 * @package libs\Request
 */
class Request
{
    const REQUEST_HTTP_OK = 200;
    const REQUEST_HTTP_CREATED = 201;
    const REQUEST_HTTP_BAD_REQUEST = 400;
    const REQUEST_HTTP_FORBIDDEN = 403;
    const REQUEST_HTTP_NOT_FOUND = 404;

    const REQUEST_HTTP_BAD_PARAMETERS_MESSAGE = 'Please, specify the correct request parameters';
    const REQUEST_HTTP_BODY_MESSAGE = 'Please, specify body of request';

    /**
     * getBody
     * 
     * Return body of query
     *
     * @access public
     * @return array
     */
    public function getBody()
    {
        $body = file_get_contents('php://input');

        if (!$body) {
            throw new RequestException(Request::REQUEST_HTTP_BODY_MESSAGE, Request::REQUEST_HTTP_BAD_REQUEST);
        }

        return $body;
    }

    /**
     * getHeaders
     * 
     * Return all headers of request
     *
     * @access public
     * @return array
     */
    public function getHeaders()
    {
        return getallheaders();
    }
}