<?php

namespace libs\Db;

/**
 * DbConfig
 * 
 * @package libs\Db
 */
class DbConfig
{
    const ERROR_EMPTY_HOST = "Empty value host";
    const ERROR_EMPTY_DATABASE = "Empty value data base name";
    const ERROR_EMPTY_LOGIN =  "Empty value login";
    const ERROR_EMPTY_DRIVER = "Empty value driver";
    const ERROR_CONNECT = "Failed connect to database";
    const EMPTY_QUERY_PARAMS = "Empty qeury";
    const ERROR_QUERY = "Something went wrong. Check your sql query.";
    const ERROR_QUERY_ARGS = "Empty arguments in prepare query";
}