<?php

class Route
{
    private $uri;

    public function __construct($uri)
    {
        $route = substr($uri, 1);
        $route = explode("?", $route);
        $route = explode("/", $route[0]);
        $route = array_diff($route, array('drink-water'));

        $this->uri = array_values($route);
    }

    public function redirect($method)
    {
        switch ($this->uri[0]) {
            case 'users':
                return UserEndpoint::getResponse($this->uri, $method);
                break;
            case 'login':
                return AuthEndpoint::getResponse($this->uri, $method);
                break;
            default:
                return ['', 404];
                break;
        }
    }
}
