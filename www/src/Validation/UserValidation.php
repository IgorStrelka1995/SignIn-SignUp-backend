<?php

namespace App\Validation;

use App\Model\User;

/**
 * UserValidation
 * 
 * @package App\Validation
 */
class UserValidation
{
    /**
     * Array of errors
     *
     * @access private
     * @var array
     */
    private $errors = [];

    /**
     * User
     *
     * @access public
     * @var obj User
     */
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * validName
     * 
     * Validate user's name
     *
     * @access public
     * @param string $name
     * @return self
     */
    public function validName(string $name): self
    {
        if (empty($name)) {
            $this->errors = 'Field name should not be blank';
        }

        if (50 < strlen($name)) {
            $this->errors = 'Field name should not be longer than 50 characters';
        }

        return $this;
    }

    /**
     * validSurname
     * 
     * Validate user's surname
     *
     * @access public
     * @param string $surname
     * @return self
     */
    public function validSurname(string $surname): self
    {
        if (empty($surname)) {
            $this->errors = 'Field surname should not be blank';
        }

        if (50 < strlen($surname)) {
            $this->errors = 'Field surname should not be longer than 50 characters';
        }

        return $this;
    }

    /**
     * validPassword
     * 
     * Validate user's password
     *
     * @access public
     * @param string $password
     * @return self
     */
    public function validPassword(string $password): self
    {
        if (empty($password)) {
            $this->errors = 'Field password should not be blank';
        }

        if (100 < strlen($password)) {
            $this->errors = 'Field password should not be longer than 100 characters';
        }

        if (6 > strlen($password)) {
            $this->errors = 'Field password should not be less than 6 characters';
        }

        if (!preg_match("/(?=.*[a-zA-Z])/", $password)) {
            $this->errors = 'Field password should has a letters';
        }

        if (!preg_match("/(?=.*[0-9])/", $password)) {
            $this->errors = 'Field password should has a numbers';
        }

        if (!preg_match("/(?=.*[!@#$%^&*])/", $password)) {
            $this->errors = 'Field password should has special symbols';
        }

        return $this;
    }

    /**
     * validPasswordAuth
     * 
     * Validate user's password at authentication user
     *
     * @access public
     * @param string $password
     * @return self
     */
    public function validPasswordAuth(string $password): self
    {
        if (empty($password)) {
            $this->errors = 'Field password should not be blank';
        }

        return $this;
    }

    /**
     * validEmail
     * 
     * Validate user's email
     *
     * @access public
     * @param string $email
     * @return self
     */
    public function validEmail(string $email): self
    {
        if (100 < strlen($email)) {
            $this->errors = 'Field email should not be blank';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors = 'Field email is not valid';
        }

        return $this;
    }

    /**
     * validUniqueEmail
     * 
     * Validate is user's email unique
     *
     * @access public
     * @param string $email
     * @return self
     */
    public function validUniqueEmail(string $email): self
    {
        $email = $this->user->getUserByEmail($email);
        if ($email) {
            $this->errors = 'User with this email has already registered';
        }

        return $this;
    }

    /**
     * validGender
     * 
     * Validate user's gender
     *
     * @access public
     * @param string $gender
     * @return self
     */
    public function validGender(string $gender): self
    {
        if (empty($gender)) {
            $this->errors = 'Field gender should not be blank';
        }

        if ("M" !== strtoupper($gender) && "F" !== strtoupper($gender)) {
            $this->errors = 'Field gender is not valid';
        }

        return $this;
    }

    /**
     * validCountry
     * 
     * Validate user's country
     *
     * @access public
     * @param string $country
     * @return self
     */
    public function validCountry(string $country): self
    {
        if (empty($country)) {
            $this->errors = 'Field country should not be blank';
        }

        if (50 < strlen($country)) {
            $this->errors = 'Field country should not be longer than 50 characters';
        }

        return $this;
    }

    /**
     * validCity
     *
     * Validate user's city
     * 
     * @access public
     * @param string $city
     * @return self
     */
    public function validCity(string $city): self
    {
        if (empty($city)) {
            $this->errors = 'Field city should not be blank';
        }

        if (50 < strlen($city)) {
            $this->errors = 'Field city should not be longer than 50 characters';
        }

        return $this;
    }

    /**
     * validId
     *
     * Validate user's id
     * 
     * @access public
     * @param $id
     * @return self
     */
    public function validId($id): self
    {
        if (empty($id)) {
            $this->errors = 'Field id should not be blank';
        }

        if (!is_numeric($id)) {
            $this->errors = 'Field id should be numeric';
        }

        return $this;
    }

    /**
     * render
     * 
     * Return array of errors
     *
     * @access public
     * @return void
     */
    public function render()
    {
        return $this->errors;
    }
}