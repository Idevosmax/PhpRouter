<?php 

use Maxi\Router;

function dd($item) {
    echo "<pre>";
    print_r($item);
    echo "</pre>";
    die();
}
    function dump($item){
        echo "<pre>";
        print_r($item);
        echo "</pre>";
    }

function route($route, array $params = [] ) {
    $foundRoute = Router::findRouteByName($route)[0]['route'] ?? null;
    $routeParams = Router::findRouteByName($route)[0]['params'] ?? null;
    $url = null;
   
    if(!empty($routeParams)){
        if(empty($params)){
            return "this route requires a parameter";
        }else{
            if(count($routeParams) == count($params)){
                $url =  array_reduce($params, function($carry,$item){
                    return   $carry . "/" . $item;
                });
                $url = $foundRoute.$url;
          
                return $url;
            }else{
                $countParams = count($routeParams);
                return "the route requires $countParams parameters";
            }
        }
        

       
    }
    
    

}
