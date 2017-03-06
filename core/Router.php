<?php

namespace core;

class Router
{
    /**
     * Available routes
     * 
     * @var array
     */
    private $routes;
    
    /**
     *  Current uri from request
     * 
     * @var string
     */
    private $uri;
    
    
    public function __construct() {
        $this->routes = $this->getRoutes();
        $this->uri = $this->getURI();
        $this->run();
    }
    
    /**
     * Determines controller,action, params
     * and call createController method
     */
    protected function run(){
                $programRoute = $this->findProgramRoute();
                $parts = explode('/', $programRoute);
                $controller = "controllers\\" .  ucfirst(array_shift($parts) . "Controller");
                $action = lcfirst(array_shift($parts));
                $params = $parts;
                $this->createController($controller,$action,$params);
    }
    
    /**
     * Create a controller and call an $action
     * with transmitted parameters
     * 
     * @param string $controller
     * @param string $action
     * @param array $params
     * 
     */
    protected function createController($controller,$action,$params) {
        $object = new $controller();
        call_user_func_array(array($object, $action), $params);
    }
    
    /**
     * Find a program route, which will
     * consist of  controller,action and params
     * 
     * @return string
     */
    protected function findProgramRoute(){
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $this->uri)) {
                $programRoute = preg_replace("~$uriPattern~", $path, $this->uri);
                break;
            }
        }
        return $programRoute;
    }
    /**
     * Get an URI from request
     * @return string
     */
    protected function getURI(){
        return substr(trim($_SERVER['REQUEST_URI']), 1);
    }

    /**
    * Get all available routes from config/routes.php
    * @return array
    */
    protected function getRoutes() {
        return require_once('config/routes.php');       
    }
}
