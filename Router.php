<?php

namespace Routerion;

use Exception;
use Closure;

class Router
{

    /**
     * Hold all defined routes
     * @var array
     */
    public $definedRoutes = [];


    /**
     * Hold HTTP request method
     */
    public $requestMethod;

    /**
     * Hold requested URL
     * @var string
     */
    public $requestUrl;


    /**
     * A flag to detect if requested url matched with any defined routes
     * @var boolean
     */
    public $matched = false;


    /**
     * Hold parameters of current checking route
     * @var array
     */
    private $requestParameters = [];


    /**
     * Valid HTTP request methods
     * @var array
     */
    private $validMethods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
    ];


    /**
     * Get the requested url and validate it
     *
     * @return void
     */

    public function __construct()
    {
        $current_dir = dirname($_SERVER['SCRIPT_NAME']);
        $requested_url = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
        $this -> requestUrl = $this -> validateUrl(str_replace($current_dir, '', $requested_url));
    }


    /**
     * Trim preceding slash from url
     *
     * @param string $url
     *
     * @return string
     */
    public function validateUrl($url)
    {
        return substr($url, 0, 1) == '/' ? substr($url, 1) : $url;
    }


    /**
     * Validate route method
     *
     * @param string $name
     * @param array $arguments
     *
     * @throws Exception on invalid request method
     * @return void
     */
    public function __call($name, $arguments)
    {
        $name =  strtoupper($name);

        $requestMethod = (
        isset($_POST['_method'])
        && in_array($method = strtoupper($_POST['_method']), ['PUT','DELETE'])
        ) ? $method : $_SERVER['REQUEST_METHOD'];

        if($requestMethod <> $name){
        return;
        }
        if(in_array($name, $this -> validMethods)) {
        $this -> requestMethod = $requestMethod;
        $route = $arguments[0];
        $action = $arguments[1];
        $this -> definedRoutes[$requestMethod][$this -> validateUrl($route)] = $action;
        }
        else throw new Exception('Method not allowed');
    }


    /**
     * Start find matching route with request url
     *
     * @throws Exception on invalid route action
     *
     * @return mixed
     */
    public function run()
    {
        $requested_url = explode('/', $this -> requestUrl);

        if(!empty($this -> definedRoutes[$this -> requestMethod])){
        foreach($this -> definedRoutes[$this -> requestMethod] as $route => $action) {

            // If requested url matches with any defined routes, stop the operation
            if($this -> matched) {
            break;
            }

            $route = explode('/', $route);
            $route_depth = count($route);

            // Check for defined route parameters
            for($i = 0; $i < $route_depth; $i++) {
            if(preg_match('/\{([\w?]+?)\}/',$route[$i])) {

                if(isset($requested_url[$i])) {
                array_push($this -> requestParameters, $requested_url[$i]);

                // replace defined route parameters with peer request url parameter for final comparison
                $route[$i] = $requested_url[$i];
                }
            }
            }

            // Check for unreplaced route parameters and delete them if are optional parameters (for final comparison)
            for($j = 0; $j < $route_depth; $j++) {
            if(preg_match('/\{([\w]+?)\?}/', $route[$j])) {
                unset($route[$j]);
            }
            }

            $route = implode('/', $route);

            // Final comparision. Check requested url is equal to current checking route
            if($route == $this -> requestUrl) {
            $this -> matched = true;
            if($action instanceof Closure) {
                return call_user_func_array($action, $this -> requestParameters);
            }
            elseif($this -> isController($action)) {
                return $this -> loadController($action);
            }
            else throw new Exception('Invalid action for route');

            break;
            }
            else {
            $this -> reset();
            }
        }
        }

        if($this -> matched === false){
        echo '<h1>404 Not Found</h1>';
        header('HTTP/1.0 404 Not Found');
        }
    }


    /**
     * Check route action is a controller
     *
     * @param string $action
     *
     * @return boolean
     */
    public function isController($action)
    {
        return (bool) preg_match("/^[A-Za-z0-9\\\]+@[A-Za-z0-9_]+$/", $action);
    }


    /**
     * Load controller
     *
     * @param string $action
     * @throws Exception when controller file doesn't exist
     * @throws Exception when specified method doesn't exist
     * @return mixed
     */
    public function loadController($action)
    {
        $action = explode('@', $action);
        $controller = $action[0];
        $method = $action[1];
        $controller_file = realpath(CONTROLLER_PATH . '/' . $controller.'.php');

        if($controller_file !== false) {
        include $controller_file;
        $controller = CONTROLLER_NAMESPACE. '\\' . $controller;
        $controller = new $controller;
        if(method_exists($controller, $method)) {
            return call_user_func_array([$controller, $method], $this -> requestParameters);
        }
        else throw new Exception("Method $method doesn\'t exist");
        }
        else throw new Exception('Couldn\'t find Controller file');
    }


    /**
     * Reset grabbed parameters from request url if request url didn't match with current checking route
     *
     * @return void
     */
    public function reset()
    {
        $this -> requestParameters = [];
    }

}
