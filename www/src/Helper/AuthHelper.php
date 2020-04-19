<?php

namespace App\Helper;

use App\Service\Auth;

/**
 * AuthHelper
 * 
 * @package App\Helper
 */
class AuthHelper
{
    /**
     * Auth
     *
     * @access private
     * @var obj Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Check auth user and user's token
     *
     * @access public
     * @param int $data
     * @return boolean|array
     */
    public function isAuth(int $data)
    {
        $this->auth->isAuthUser();
        $this->auth->isUserOwnerToken($data);
    }
}