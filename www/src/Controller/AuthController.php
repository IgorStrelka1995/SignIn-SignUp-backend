<?php

namespace App\Controller;

use libs\Request\Request;
use App\Service\Auth;
use App\Validation\UserValidation;
use libs\Response\Response;
use App\Model\User;
use App\Model\Token;
use App\Helper\AuthHelper;
use libs\Request\RequestException;

/**
 * AuthController
 * 
 * @package App\Controller
 */
class AuthController
{
    /**
     * Request
     *
     * @access private
     * @var obj Request
     */
    private $request;

    /**
     * Auth
     *
     * @access private
     * @var obj Auth
     */
    private $auth;

    /**
     * UserValid
     *
     * @access private
     * @var obj UserValidation
     */
    private $userValid;

    /**
     * User
     *
     * @access private
     * @var obj User
     */
    private $user;

    /**
     * Token
     *
     * @access private
     * @var obj Token
     */
    private $token;

    /**
     * AuthHelper
     *
     * @var obj 
     */
    private $authHelper;

    public function __construct()
    {
        $this->request      = new Request();
        $this->userValid    = new UserValidation();
        $this->user         = new User();
        $this->token        = new Token();
        $this->auth         = new Auth($this->user, $this->token);
        $this->authHelper   = new AuthHelper($this->auth);
    }

    /**
     * Sign In user
     *
     * @access public
     * @throws RequestException
     * @return obj Response
     */
    public function postLogin()
    {
        $body = json_decode($this->request->getBody(), true);

        if ($body && array_key_exists('email', $body) && array_key_exists('password', $body)) {
            $validation = $this->userValid->validEmail($body['email'])->validPasswordAuth($body['password'])->render();

            if (!empty($validation)) {
                throw new RequestException($validation, Request::REQUEST_HTTP_BAD_REQUEST);
            } else {
                $response = $this->auth->login($body['email'], $body['password']);
            }
        } else {
            throw new RequestException(Request::REQUEST_HTTP_BAD_PARAMETERS_MESSAGE, Request::REQUEST_HTTP_BAD_REQUEST);
        }
      
        return new Response($response['content'], $response['status']);
    }

    /**
     * Log Out user
     *
     * @access public
     * @throws RequestException
     * @return obj Response
     */
    public function postLogout()
    {
        $body = json_decode($this->request->getBody(), true);

        if ($body && array_key_exists('id', $body)) {
            $validation = $this->userValid->validId($body['id'])->render();

            if (!empty($validation)) {
                throw new RequestException($validation, Request::REQUEST_HTTP_BAD_REQUEST);
            } else {
                $this->authHelper->isAuth($body['id']);
                
                $response = $this->auth->logout($body['id']);
            }
        } else {
            throw new RequestException(Request::REQUEST_HTTP_BAD_PARAMETERS_MESSAGE, Request::REQUEST_HTTP_BAD_REQUEST);
        }
            
        return new Response($response['content'], $response['status']);
    }

    /**
     * Check is user auth
     *
     * @access public 
     * @throws RequestException
     * @return obj Response
     */
    public function postIsAuth()
    {
        $body = json_decode($this->request->getBody(), true);

        if ($body && array_key_exists('id', $body)) {
            $validation = $this->userValid->validId($body['id'])->render();

            if (!empty($validation)) {
                throw new RequestException($validation, Request::REQUEST_HTTP_BAD_REQUEST);
            } else {
                $this->authHelper->isAuth($body['id']);

                $response = [
                    "status" => Request::REQUEST_HTTP_OK,
                    "content" => "success"
                ];
            }
        } else {
            throw new RequestException(Request::REQUEST_HTTP_BAD_PARAMETERS_MESSAGE, Request::REQUEST_HTTP_BAD_REQUEST);
        }

        return new Response($response['content'], $response['status']);
    }
}