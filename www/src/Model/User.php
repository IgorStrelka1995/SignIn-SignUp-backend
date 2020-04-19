<?php

namespace App\Model;

use libs\Db\Db;
use PDO;

/**
 * User
 * 
 * @package App\Model
 */
class User
{
    /**
     * getUser
     * 
     * Get user card from `user` table by user id
     *
     * @access public
     * @param int $id
     * @return array
     */
    public function getUser(int $id): array
    {
        $query = "SELECT u.id as id, u.name as name, u.surname as surname, u.email as email, u.gender as gender, u.country as country, u.city as city, i.name as image " . 
                 "FROM user as u INNER JOIN image i ON u.image_id = i.id " . 
                 "WHERE u.id = :id";

        $stmt = Db::prepare($query, [':id' => $id])->fetchAll(PDO::FETCH_ASSOC);

        if ($stmt) {
            return $stmt;
        } else {
            return array();
        }
        
    }

    /**
     * createUser
     *
     * Create new user
     * 
     * @access public
     * @param array $data
     * @return int $id
     */
    public function createUser(array $data): string
    {
        $query = "INSERT INTO user " .
                 "(name, surname, password, email, gender, country, city, image_id) " . 
                 "VALUES (:name, :surname, :password, :email, :gender, :country, :city, :image_id)"
        ;

        $stmt = Db::prepare($query, [
            ':name' => $data['name'],
            ':surname' => $data['surname'],
            ':password' => $data['password'],
            ':email' => $data['email'],
            ':gender' => $data['gender'],
            ':country' => $data['country'],
            ':city' => $data['city'],
            ':image_id' => $data['image_id']
        ]);

        return Db::lastInsertId();
    }

    public function addImage($imageName)
    {
        $query = "INSERT INTO image (name) VALUES (:name)";

        $stmt = Db::prepare($query, [
            ':name' => $imageName,
        ]);

        if ($stmt) {
            return Db::lastInsertId();
        } else {
            return array();
        }
    }

    /**
     * getUserByEmail
     *
     * Get user card from `user` table by user's email
     * 
     * @access public
     * @param string $email
     * @return array
     */
    public function getUserByEmail(string $email): array
    {
        $query = "SELECT id, name, surname, password, email, gender FROM user WHERE email = :email";

        $stmt = Db::prepare($query, [
            ':email' => $email
        ])->fetch(PDO::FETCH_ASSOC);

        if ($stmt) {
            return $stmt;
        } else {
            return array();
        }
    }
}