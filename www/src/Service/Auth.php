<?php

namespace App\Service;

use App\Model\User;
use App\Model\Token;
use libs\Request\Request;
use App\Validation\AuthVallidation;
use libs\Request\RequestException;

/**
 * Auth
 * 
 * @package App\Service
 */
class Auth
{
    const AUTH_ERROR_EXPIRED = "Authorization token is expired";
    const AUTH_ERROR_INVALID = "Authorization token is invalid";
    const AUTH_ERROR_ENTER_TOKEN = "Please provide authorization token";
    const AUTH_ERROR_ENTER_HEADER = "Please provide authorization header";
    const AUTH_ERROR_ACCESS = "Access denied";
    const AUTH_USER_LOGOUT = "User logout";
    const AUTH_USER_NOT_FOUND = "User not found";
    const AUTH_ERROR_BAD_CREDENTIALS = "Invalid credentials";

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
     * AuthValid
     *
     * @var obj AuthValidation
     */
    private $authValid;
    
    public function __construct(User $user, Token $token)
    {
        $this->user     = $user;
        $this->token    = $token;
        $this->authValid = new AuthVallidation();
    }

    /**
     * Sign In user
     *
     * @access public
     * @param string $email
     * @param string $password
     * @throws RequestException
     * @return array
     */
    public function login(string $email, string $password): array
    {
        $user = $this->user->getUserByEmail($email);

        if ($user) {
            $email = $user["email"];
            $hashPassword = $user["password"];

            if (password_verify($password, $hashPassword)) {
                $userId = $user["id"];
                $tokenInfo = $this->token->getTokenInfoByUserId($user["id"]);
                
                if ($tokenInfo) {
                    $this->token->removeToken($userId);
                }

                $token  = md5(uniqid(rand(),1));
                $expire = date("Y-m-d H:i:s", strtotime("+1 day"));
                
                $this->token->addToken($token, $userId, $expire);
    
                $result = [
                    "status" => Request::REQUEST_HTTP_CREATED,
                    "content" => [
                        "token" => $token,
                        "userId" => $userId, 
                        "expire" => $expire
                        ]
                    ];
            } else {
                throw new RequestException(Auth::AUTH_ERROR_BAD_CREDENTIALS, Request::REQUEST_HTTP_BAD_REQUEST);
            }
        } else {
            throw new RequestException(Auth::AUTH_ERROR_BAD_CREDENTIALS, Request::REQUEST_HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * logout
     * 
     * Log Out user
     *
     * @access public
     * @param int $userId
     * @throws RequestException
     * @return array
     */
    public function logout(int $userId): array
    {
        $tokenInfo = $this->token->getTokenInfoByUserId($userId);

        if ($tokenInfo) {
            $this->token->removeToken($userId);
            
            $result = [
                "status" => 200, 
                "content" => Auth::AUTH_USER_LOGOUT
            ];
        } else {
            throw new RequestException(Auth::AUTH_USER_NOT_FOUND, Request::REQUEST_HTTP_NOT_FOUND);
        }

        return $result;
    }

    /**
     * isAuthUser
     * 
     * Check is user authorization
     *
     * @access public
     * @throws RequestException
     * @return boolean|array
     */
    public function isAuthUser(): array
    {
        $token = $this->authValid->checkAuthHeader();
        $response = $this->authValid->checkUserToken($token);

        $expire = $response["expire"];
        $today  = strtotime("now");  

        if ($expire <= $today) {
            throw new RequestException(Auth::AUTH_ERROR_EXPIRED, Request::REQUEST_HTTP_FORBIDDEN);
        }

        return $response;
    }

    /**
     * isUserOwnerToken
     * 
     * Check is user owner token
     *
     * @access public
     * @param int $userId
     * @throws RequestException
     * @return boolean|array
     */
    public function isUserOwnerToken(int $userId): array
    {
        $token = $this->authValid->checkAuthHeader();
        $response = $this->authValid->checkUserToken($token);

        $tokenUserId  = $response["user_id"];
        $clientUserId  = $userId;

        if ($tokenUserId != $clientUserId) {
            throw new RequestException(Auth::AUTH_ERROR_ACCESS, Request::REQUEST_HTTP_FORBIDDEN);
        }

        return $response;
    }
}