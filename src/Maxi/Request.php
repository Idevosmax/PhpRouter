<?php 
namespace Maxi;
class Request {
    
    // private $getters = $_GET
    public static function isGet() {
        if($_SERVER['REQUEST_METHOD'] == "GET") return true;
        return false;
    }

    public static function isPost() {
        if($_SERVER['REQUEST_METHOD'] == "POST") return true;
        return false;
    }

    public static function uri(){
       
        if(empty($_SERVER["PATH_INFO"])){
            return "/";
        }else {
            $url = $_SERVER["PATH_INFO"] > 1 ? rtrim($_SERVER["PATH_INFO"], '/') : $_SERVER["PATH_INFO"] ;
          //  echo $url;
        return $url;
        }
        
    }

    public function get($name) {
        return $_GET[$name];
    }

    public function getAll() 
    {
      return $_GET;
    }

    public function postAll() 
    {
        return $_POST;
    }

    public function post($name) {
        $_POST[$name];
    }

    public function __get($name)
    {
        return $_GET[$name]??$_POST[$name];
    }
}