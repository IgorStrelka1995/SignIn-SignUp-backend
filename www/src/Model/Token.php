<?php

namespace App\Model;

use libs\Db\Db;
use PDO;

/**
 * Token
 * 
 * @package App\Model
 */
class Token
{
    /**
     * addToken
     * 
     * Create new token for user
     *
     * @access public
     * @param string $token
     * @param integer $user_id
     * @param string $expire
     * @return void
     */
    public function addToken(string $token, int $user_id, string $expire)
    {
        $query = "INSERT INTO token (token, user_id, expire) VALUES (:token, :user_id, :expire)";

        $stmt = Db::prepare($query, [
            ':token' => $token,
            ':user_id' => $user_id,
            ':expire' => $expire
        ]);

        return $stmt;
    }

    /**
     * getTokenInfoByToken
     * 
     * Get information from table `token` by user's token
     *
     * @access public
     * @param string $token
     * @return array
     */
    public function getTokenInfoByToken(string $token): array
    {
        $query = "SELECT id, user_id, token, UNIX_TIMESTAMP(expire) AS expire FROM token WHERE token = :token";

        $stmt = Db::prepare($query, [
            ':token' => $token
        ])->fetch(PDO::FETCH_ASSOC);

        if ($stmt) {
            return $stmt;
        } else {
            return array();
        }
    }

    /**
     * getTokenInfoByUserId
     * 
     * Get information from table `token` by user's id
     *
     * @access public
     * @param int $userId
     * @return array
     */
    public function getTokenInfoByUserId(int $userId): array
    {
        $query = "SELECT id, user_id, token, UNIX_TIMESTAMP(expire) AS expire FROM token WHERE user_id = :user_id";

        $stmt = Db::prepare($query, [
            ':user_id' => $userId
        ])->fetch(PDO::FETCH_ASSOC);

        if ($stmt) {
            return $stmt;
        } else {
            return array();
        }
    }

    /**
     * removeToken
     * 
     * Remove user's token by user id
     *
     * @access public
     * @param int $userId
     * @return void
     */
    public function removeToken(int $userId)
    {
        $query = "DELETE FROM token WHERE user_id = :user_id";

        $stmt = Db::prepare($query, [
            ':user_id' => $userId
        ]);

        return $stmt;
    }
}