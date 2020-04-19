<?php

namespace App\Service;

use App\Service\Auth;
use libs\Request\Request;
use libs\Request\RequestException;

class Upload
{
    const IMAGE_DIR_PATH = '/public/images/';

    const UPLOAD_ERROR_NO_FOUND = 'The image does not find';
    const UPLOAD_ERROR_NOT_SAVE = 'The image is not save';
    const UPLOAD_ERROR_WRONG_FORMAT = 'File format is not valid';
    const UPLOAD_ERROR_FILE_ENTER = 'Please enter the file';

    /**
     * Upload image
     *
     * @param array $file
     * @throws RequestException
     * @return string
     */
    public function uploadImage(array $file): string
    {
        if ($file) {
            if (array_key_exists('user_image', $file)) {
                $tmp_file = $file['user_image']['tmp_name'];
                $tmp_name = $file['user_image']['name'];
                $tmp_type = $file['user_image']['type'];

                $fileType = substr($tmp_type, 6);

                if ("jpeg" == $fileType || "png" == $fileType || "gif" == $fileType || "jpg" == $fileType) {
                    $imageName = uniqid(rand(),1) . $tmp_name;
                    $upload_dir = __DIR__ . "/../../public/images/" . $imageName;

                    if (is_uploaded_file($tmp_file)) {
                        if (move_uploaded_file($tmp_file, $upload_dir)) {
                            $result = $imageName;
                        } else {
                            throw new RequestException(Upload::UPLOAD_ERROR_NOT_SAVE, Request::REQUEST_HTTP_BAD_REQUEST);
                        }
                    } else {
                        throw new RequestException(Upload::UPLOAD_ERROR_NO_FOUND, Request::REQUEST_HTTP_BAD_REQUEST);
                    }
                } else {
                    throw new RequestException(Upload::UPLOAD_ERROR_WRONG_FORMAT, Request::REQUEST_HTTP_BAD_REQUEST);
                }
            } else {
                throw new RequestException(Request::REQUEST_HTTP_BAD_PARAMETERS_MESSAGE, Request::REQUEST_HTTP_BAD_REQUEST);
            }
        } else {
            throw new RequestException(Upload::UPLOAD_ERROR_FILE_ENTER, Request::REQUEST_HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * Get path to image file
     *
     * @param string $imageName
     * @return string
     */
    public function getImage(string $imageName): string
    {
        return URL_PROTOCOL . URL_HOST . Upload::IMAGE_DIR_PATH . $imageName;
    }
}