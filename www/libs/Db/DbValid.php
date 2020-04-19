<?php

namespace libs\Db;

use libs\Db\Exception\DbException;
use libs\Db\DbConfig;

/**
 * DbValid
 * 
 * @package libs\Db
 */
class DbValid
{
    /**
     * emptySql
     *
     * checks for emptiness for sql query
     *
     * @param string $sql sql query
     * @access public
     * @throws DbException
     * @return true
     */
    public function emptySql($sql)
    {
        if(empty($sql)) {
            throw new DbException(DbConfig::EMPTY_QUERY_PARAMS);
        }

        return true;
    }

    /**
     * emptyArgs
     *
     * checks for emptiness arguments for prepare method
     *
     * @param array $args arguments for prepare query
     * @access public
     * @throws DbException
     * @return true
     */
    public function emptyArgs($args)
    {
        if(empty($args)) {
            throw new DbException(DbConfig::ERROR_QUERY_ARGS);
        }

        return true;
    }
}