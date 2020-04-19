<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Access-Control-Allow-Origin');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 ");
    exit;
}

require_once "autoload.php";

use libs\Router\Router;
use libs\Db\Db;
use libs\Request\RequestException;
use libs\Db\Exception\DbException;
use libs\Router\Exception\RouterException;

try {
    $database = require_once(__DIR__ . "/config/database.php");

    Db::setDriver($database['driver']);
    Db::setHost($database['host']);
    Db::setDbName($database['db_name']);
    Db::setLogin($database['username']);
    Db::setPassword($database['password']);
    Db::connect();

    $router = new Router();
    echo $router->run();
} catch (RequestException $re) {
    echo $re->getMessage();
} catch (RouterException $rte) {
    echo json_encode(["response" => $rte->getMessage()]);
} catch(DbException $db) {
    echo json_encode(["response" => $db->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["response" => $e->getMessage()]);
}
