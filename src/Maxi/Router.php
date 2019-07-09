<?php 
namespace Maxi;
use Maxi\Request;
class Router{

    public static $routes = [];
    public static $params = [];
    public static function register($route, $type, $function){
        preg_match_all("/:([a-z]*)/", $route, $requestParam);
        $route = \preg_replace("/\/:.*/", "", $route);
        self::$routes[$route] = [
            "function" => $function,
            "type" => $type,
            "params" => $requestParam[1]
        ];
    }

    public static function get($route,$function){

        if(Request::isGet()) {
            self::register($route,"get", $function);
        }
    }


    public static function post($route,$function){
        if(Request::isPost()) {
            self::register($route,"post", $function);
        }
    }

    public static function findRoute(){
        // echo "<pre>";
        // print_r(self::$routes);
        
        $url =  Request::uri();
        // print_r($url);
        // die();
        $action = self::$routes[$url] ??  null;
        
        if(empty($action)){
           
           $foundRoute = self::myCallback($url);
             $action = self::$routes[$foundRoute] ?? null;
            if(empty($action)){
                return null;
            }else{
                if (count($action['params']) == count(self::$params)){
                 $paramArray = array_combine($action['params'],self::$params);
                 $action['params'] = $paramArray;
                return $action;
             }else {
                 return null;
             }
            }
             
             
        }else{
            if(empty($action['params'])){
                return $action;
            }else{
                return null;
            }
            
        }
        
        
    }

     public static function myCallback($url) {
        $trim = ltrim($url,"/");
        $explodeRequestUri = explode("/",$trim);
        array_unshift(self::$params, array_pop($explodeRequestUri)) ;
        $reduced = array_reduce($explodeRequestUri,function ($carry,$item){
           return $carry."/".$item;
        });
        $action = self::$routes[$reduced] ?? null;
       
        $foundRoute = $reduced;
        if (empty($action) && strpos($foundRoute,"/" > 2)){
            return self::myCallback($foundRoute);
            
        }else{

            return $foundRoute;
        }
       
    }
    public static function run()
    {
        
        $action =  self::findRoute();
        echo "<pre>";
        \print_r($action);
        print_r(self::$params);
        $params = $action['params'];
        
        if(empty($action)){
            \header("HTTP/1 404 Not Found");
            echo "404 Not Found";
        }else{
            $function = $action['function'];
            if(is_string($function)){
            echo "We are coming for Controller";
        } else if(is_callable($function)) {
            $req = new Request();
            echo $function($req, $params);
        }
        }
        
         
    }
}