<?php

namespace App\Service;

/**
 * PasswordHash
 * 
 * @package App\Service
 */
class PasswordHash
{
    /**
     * hashPassword
     * 
     * Hash user's password
     *
     * @access public
     * @param string $password
     * @param array $options
     * @return string
     */
    public function hashPassword(string $password, array $options = []): string
    {
        return password_hash($password, PASSWORD_DEFAULT, $options);
    }
}