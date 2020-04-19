<?php

namespace App\Controller;

use libs\Response\Response;
use App\Model\User;
use App\Validation\UserValidation;
use App\Service\PasswordHash;
use libs\Request\Request;
use App\Service\Auth;
use App\Model\Token;
use App\Helper\AuthHelper;
use App\Service\Upload;
use libs\Request\RequestException;

/**
 * UserController
 * 
 * @package App\Controller
 */
class UserController
{
    /**
     * User
     *
     * @access private
     * @var obj User
     */    
    private $user;

    /**
     * UserValid
     *
     * @access private
     * @var obj UserValidation
     */
    private $userValid;

    /**
     * PasswordHash
     *
     * @access private
     * @var obj PasswordHash
     */
    private $passwordHash;

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
     * Token
     *
     * @access private
     * @var obj Token
     */
    private $token;

    /**
     * AuthHelper
     *
     * @access private
     * @var obj 
     */
    private $authHelper;

    /**
     * Upload
     *
     * @access private
     * @var obj Upload
     */
    private $upload;

    public function __construct()
    {
        $this->user         = new User();
        $this->userValid    = new UserValidation();
        $this->passwordHash = new PasswordHash();
        $this->request      = new Request();
        $this->token        = new Token();
        $this->auth         = new Auth($this->user, $this->token);
        $this->authHelper   = new AuthHelper($this->auth);
        $this->upload       = new Upload();
    }

    /**
     * Get user card by id
     *
     * @access public
     * @param $id
     * @throws RequestException
     * @return obj Response
     */
    public function getUser($id)
    {
        $validation = $this->userValid->validId($id)->render();

        if (!empty($validation)) {
            throw new RequestException($validation, Request::REQUEST_HTTP_BAD_REQUEST);
        } else {
            $this->authHelper->isAuth($id);

            $user = $this->user->getUser($id);
            $user[0]['image'] = $this->upload->getImage($user[0]['image']); 

            $response = [
                "status" => Request::REQUEST_HTTP_OK, 
                "content" => $user
            ];
        }

        return new Response($response['content'], $response['status']);
    }

    /**
     * Sign Up new user
     *
     * @access public
     * @throws RequestException
     * @return obj Response
     */
    public function postCreate()
    {
        $keys = ['name', 'surname', 'password', 'email', 'gender', 'country', 'city'];

        if ($this->array_keys_exist($keys, $_POST)) {
                
            // Use for autologin after registration
            $password = $_POST['password'];

            $validation = $this->userValid->validName($_POST['name'])->validSurname($_POST['surname'])
                        ->validPassword($_POST['password'])->validEmail($_POST['email'])
                        ->validUniqueEmail($_POST['email'])->validGender($_POST['gender'])
                        ->validCountry($_POST['country'])->validCity($_POST['city'])->render();

            if (!empty($validation)) {
                throw new RequestException($validation, Request::REQUEST_HTTP_BAD_REQUEST);
            } else {
                $imageName = $this->upload->uploadImage($_FILES);
                $imageId = $this->user->addImage($imageName);

                $_POST['image_id'] = $imageId;
                $_POST['password'] = $this->passwordHash->hashPassword($_POST['password']);

                $this->user->createUser($_POST);
                $response = $this->auth->login($_POST['email'], $password);
            }
        } else {
            throw new RequestException(Request::REQUEST_HTTP_BAD_PARAMETERS_MESSAGE, Request::REQUEST_HTTP_BAD_REQUEST);
        }

        return new Response($response['content'], $response['status']);
    }

    private function array_keys_exist($keys, $array){
        foreach($keys as $key) {
            if(!array_key_exists($key, $array)) {
                return false;
            }
        }

        return true;
    }
}