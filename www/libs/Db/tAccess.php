<?php

namespace libs\Db;

use libs\Db\Exception\DbException;

/**
 * tAccess trait
 * 
 * Has overload methods for database params
 * 
 * @package libs\Db
 */
trait tAccess
{
    /**
     * _driver
     *
     * @access private
     * @var string
     */
    private static $driver;

    /**
     * _host
     * 
     * @access private
     * @var string
     */
    private static $host;

    /**
     * _dbName
     *
     * @access private
     * @var string
     */
    private static $dbName;

    /**
     * _login
     *
     * @access private
     * @var string
     */
    private static $login;

    /**
     * _password
     *
     * @access private
     * @var string
     */
    private static $password;

    /**
     * setDriver
     *
     * @param string $driver
     * @throws DbException
     * @return void
     */
    public static function setDriver(string $driver)
    {
        if(empty($driver)) {
            throw new DbException(DbConfig::ERROR_EMPTY_DRIVER);
        }

        self::$driver = $driver;
    }

    /**
     * setHost
     *
     * @param string $host
     * @throws DbException
     * @return void
     */
    public static function setHost($host)
    {
        if(empty($host)) {
            throw new DbException(DbConfig::ERROR_EMPTY_HOST);
        }

        self::$host = $host;
    }

    /**
     * setDbName
     *
     * @param string $dbName
     * @throws DbException
     * @return void
     */
    public static function setDbName($dbName)
    {
        if(empty($dbName)) {
            throw new DbException(DbConfig::ERROR_EMPTY_DATABASE);
        }

        self::$dbName = $dbName;
    }

    /**
     * setLogin
     *
     * @param string $login
     * @throws DbException
     * @return void
     */
    public static function setLogin($login)
    {
        if(empty($login)) {
            throw new DbException(DbConfig::ERROR_EMPTY_LOGIN);
        }

        self::$login = $login;
    }

    /**
     * setPassword
     *
     * @param string $password
     * @return void
     */
    public static function setPassword($password)
    {
        self::$password = $password;
    }
}
