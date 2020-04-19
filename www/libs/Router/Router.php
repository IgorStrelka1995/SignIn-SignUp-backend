<?php

namespace libs\Router;

use libs\Router\Exception\RouterException;
use libs\Router\RouterConfig;

/**
 * Router
 *
 * @package libs\Router
 */
class Router
{
    /**
     * Url
     *
     * @access private
     * @var array
     */
    private $url = [];

    /**
     * Resource
     *
     * @access private
     * @var string
     */
    private $resource;

    /**
     * Action
     *
     * @access private
     * @var string
     */
    private $action;

    /**
     * Args
     *
     * @access private
     * @var void
     */
    private $args;

    /**
     * getUrl
     *
     * Generate array via $_GET['PATH_INFO']
     * and delete empty values
     *
     * @access public
     * @return array url parametrs
     */
    public function getUrl()
    {
        return array_values(
            array_diff(
                explode("/", $_GET['PATH_INFO']), array("")
            )
        );
    }

    /**
     * run
     *
     * Parse url and 
     * init resource and method
     *
     * @access public
     * @throws RouterException
     * @return object 
     */
    public function run()
    {
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5)) == 'https' ? 'https://' : 'http://';
        define("URL_PROTOCOL", $protocol);
        define("URL_HOST", $_SERVER['HTTP_HOST']);

        $this->url = $this->getUrl();

        // Remove "/api/" from url
        array_shift($this->url);

        switch(count($this->url)) {
          case 3:
            list($this->resource, $this->action, $this->args) = $this->url;
            break;
          case 2:
            list($this->resource, $this->action) = $this->url;
            break;
          default:
            http_response_code(400);
            throw new RouterException(RouterConfig::ERROR_ROUTER_DEFAULT);
            break;
        }

        $this->resource = ucfirst($this->resource);
        $className = "App\\Controller\\$this->resource" . "Controller";

        if(class_exists($className)) {
            $obj = new $className();
            $method = strtolower($_SERVER['REQUEST_METHOD']);

            if(method_exists($obj, $method . ucfirst($this->action))) {
                return call_user_func(array($obj, $method . ucfirst($this->action)), $this->args);
            } else {
                http_response_code(405);
                throw new RouterException(RouterConfig::ERROR_METHOD);
            } 
        } else {
            http_response_code(404);
            throw new RouterException(RouterConfig::ERROR_RESOURCE);
        }
    }
}
