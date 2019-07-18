<?php 
namespace Maxi;
use Maxi\Request;
    /**
     * Router class handles the the assignment of routes to route table,
     * Finding of routes fromt he routes table
     * handling all request methods,
     * handling all responses.
     */
class Router{
    // static property that holds the instance of this class
    public static $instance = false;
    // the routes[] holds all the registred routes specified in the routes.php file
    public static $routes = [];
    // the params[] holds the parameters of routes which were registred with parameters
    public static $params = [];
    // the lastRoute property holds the last route the application registred 
    public static $lastRoute ="";
    // the routeNames[] holds the names of named routes
    public static $routeNames = [];
    // namedRoute[] holds all the routes that are named
    public static $namedRoute = [];



    /**
     * @register function registers all the routes defined in the routes.php file with the params below
     *
     * @param [type] $route: the route url
     * @param [type] $type: the request method of the route
     * @param [type] $function: the defined function of the route, whether a callback
     * or a class method.
     * @return void
     */
    public static function register($route, $type, $function){
        preg_match_all("/:([a-z]*)/", $route, $requestParam);
        $route = \preg_replace("/\/:.*/", "", $route);
        self::$lastRoute = $route;
        self::$routes[$route] = [
            "route" => $route,
            "function" => $function,
            "type" => $type,
            "params" => $requestParam[1]
        ];
    }

    /**
     * @get get function registers all the routes defined as get
     *
     * @param [type] $route: the url of the desired route
     * @param [type] $function: the action to be performed when the route is triggered
     * either a callback or a class method
     * @return self returns the instance of the router class for method chaining 
     * just like attaching a name to the route 
     */
    public static function get($route,$function) :self {

        if(Request::isGet()) {
            self::register($route,"get", $function);
        }
        
        return self::getInstance();
    }


    public static function post($route,$function){
        if(Request::isPost()) {
            self::register($route,"post", $function);
        }
    }
    /**
     * @findRouteByUrl searches the routes[] with the requested url to see if the routes is defined
     *
     * @param [type] $path optional, you can specify a path instead of the requested uri
     * @return returns the found route or null if not found
     */
    public static function findRouteByUrl($path = null){
        
        $url = $path ?? Request::uri();
        // finds the route by the requested uri
        $action = self::$routes[$url] ??  null;
        
        if(empty($action)){
           // if the routes is not found then it passes the uri to a @myCallBack() which uses a callback function to further find the route should incase it was defined with parameters
           $foundRoute = self::myCallback($url);
                // $foundRoute holds the route uri which was found or null if not found
             $action = self::$routes[$foundRoute] ?? null;
            
            if(empty($action)){
                
                return null;
            }else{
                // if the route was found then it checks to see if the route has parameters and assigns the parameters to their specific keys in the route[] then updates the route[]
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
                // if the route is found without parameters then it is returned but if the routes requires parameter but none was found then it triggers an exception
                return $action;
            }else{
                return null;
            }
            
        }
        
        
    }
    /**
     * @MyCallBack this function tries to find the route by using a callback that reduces the given uri on each occasion till it gets to the base or final url 
     *
     * @param [type] $url
     * @return void
     */
     public static function myCallback($url) {
        // strips the leading / from the url
        $trim = ltrim($url,"/");
        // checks if the uri contains more than one slash if not, the function ends and null is returned
        if(strpos($trim,"/") < 1){

            return null;
        
        }else{
            $explodeRequestUri = explode("/",$trim);
        // the array_unshift() removes the last item in the array on each callback
        array_unshift(self::$params, array_pop($explodeRequestUri)) ;
        // array reduce combines the array elemnts into a url that will be used to search the routes table
        $reduced = array_reduce($explodeRequestUri,function ($carry,$item){
           return $carry."/".$item;
        });
        // $reduced now holds the url that was gotten from trimming from the last / down
        $action = self::$routes[$reduced] ?? null;
       
        $foundRoute = $reduced;
        // if the route is still not found, then the function calls itself again with a uri that gets trimmed each time the function calls else the url with which the route was found with will be returned
        if (empty($action) ){
            return self::myCallback($foundRoute);
            
        }else{

            return $foundRoute;
        }
        }
        
       
       
    }
    /**
     * @run run function gets triggred once the application boots and index.php files is loaded.
     * this function is responsible for finding the requested route and sending back a response to the browser
     *
     * @return void
     */
    public static function run()
    {
       
        $action =  self::findRouteByUrl() ;
      //  dump($action);
        $params = $action['params'] ?? null;
        // if the route requested is not found, the application returns a 404 header response else, it calls the specified function of the route which then sends back the appropriate response
        if(empty($action)){
            \header("HTTP/1 404 Not Found");
            echo "404 Not Found";
        }else{
            $function = $action['function'];
            if(is_string($function)){
            echo "We are coming for Controllers";

        }else if(is_callable($function)) {
            $req = new Request();
            echo $function($req, $params);
        }
        }
        
         
    }

    public function name($name){
        /**
         * @name method defines the name of a particular route,
         * the name method is unique to a particular route
         * 
         */
        if(in_array($name,self::$routeNames)){
            echo "name $name already belongs to a route";
            print_r(self::$lastRoute[$name]) ;
        }else{
            self::$routes[self::$lastRoute]['name'] = $name;
            self::$routeNames[] = $name;
            self::$namedRoute[$name] = [
                self::$routes[self::$lastRoute]
            ];
        } 
        
    }

    /**
     * @getInstance this function returns the instance of this class
     *
     * @return self
     */
    public static function getInstance() :self {
        if(!self::$instance) return new Router;
        self::$instance = new Router;
        return self::$instance;
        
    }
    

    public static function __callStatic($name, $arguments)
    {
        echo "You are calling $name statically";
    }

        /**
         *  this function finds route by their name instead of their url
         *
         * @param [type] $name
         * @return void
         */
    public static function findRouteByName($name){
        $route = self::$namedRoute[$name];
        if (empty($route)){
            return null;
        }else{
            return $route;
        }
    }
}