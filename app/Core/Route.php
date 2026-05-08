<?php

namespace App\Core;

class Route {

    public $routes = [];

    public function add($method, $uri , $controller){
        if(is_string($controller)){
            $data = [
                'class' => $controller,
                'method' => $method,
            ];
        }

        if(is_array($controller)){
            $data = [
                'class' => $controller[0],
                'method' => $controller[1],
            ];
        }

        $this->routes[$method][$uri] = $data;
    }

    public function get($uri,$controller){
        $this->add('GET',$uri,$controller);
        return $this;
    }

    public function post($uri,$controller){
        $this->add('POST',$uri,$controller);
        return $this;
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        
        if ( ! isset( $this->routes[$httpMethod][$uri] ) ) {

            dd($uri,$httpMethod,$this);
        }

        $routeInfo = $this->routes[$httpMethod][$uri];

        $class = $routeInfo['class'];
        $method = $routeInfo['method'];

        $c = new $class;
        $c->$method();
    }
}