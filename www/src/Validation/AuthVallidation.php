<?php

namespace App\Validation;

use libs\Request\Request;
use App\Service\Auth;
use App\Model\Token;
use libs\Request\RequestException;

class AuthVallidation
{
    /**
     * Request
     *
     * @access private
     * @var obj Request
     */
    private $request;

    /**
     * Token
     *
     * @access private
     * @var obj Token
     */
    private $token;    

    public function __construct()
    {
        $this->request = new Request();
        $this->token = new Token();
    }

    /**
     * checkUserToken
     *
     * Check user's token
     * 
     * @access public
     * @param string $token
     * @throws RequestException
     * @return array token information
     */
    public function checkUserToken(string $token): array 
    {
        if ($token) {
            $tokenInfo = $this->token->getTokenInfoByToken($token);

            if ($tokenInfo) {
                $result = $tokenInfo;
            } else {
                throw new RequestException(Auth::AUTH_ERROR_INVALID, Request::REQUEST_HTTP_FORBIDDEN);
            }
        } else {
            throw new RequestException(Auth::AUTH_ERROR_ENTER_TOKEN, Request::REQUEST_HTTP_FORBIDDEN);
        }

        return $result;
    }

    /**
     * checkAuthHeader
     * 
     * Check is request has Authorization token
     *
     * @access public
     * @throws RequestException
     * @return string token
     */
    public function checkAuthHeader(): string
    {
        $headers = $this->request->getHeaders();

        if($headers && array_key_exists('Authorization', $headers)) { 
            preg_match("/^[Bearer]+ ([a-zA-Z0-9]+)$/i", $headers["Authorization"], $data);

            if ($data) {
                $result = $data[1];
            } else {
                throw new RequestException(Auth::AUTH_ERROR_ENTER_TOKEN, Request::REQUEST_HTTP_FORBIDDEN);
            }
        } else {
            throw new RequestException(Auth::AUTH_ERROR_ENTER_HEADER, Request::REQUEST_HTTP_FORBIDDEN);
        }

        return $result;
    }
}