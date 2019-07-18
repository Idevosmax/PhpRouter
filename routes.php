<?php
use Maxi\Router;
use Maxi\Request;


Router::get("/", function ($req) {
    return " 
    <html>  
    <head>
    <title> Maxi's routing system home page </title>
    </head>
    <body>
    </body>
    </html>
    
    ";
})->name('stanley');
Router::get("/home/:id/:name", function ($req, $params) {
    // echo $req->get('name');
    echo "<pre>";
    print_r($params);
})->name('stanl');

Router::get("/login/:id", function ($req) {
    echo $req->get('name');
});


Router::get("/register", function ($req) {
  
  

  return " 
  <html>  
  <head>
  <title> Maxi's routing system Sign up Page </title>
  </head>
  <body>
  </body>
  </html>
  ";
})->name('kosi');

Router::get("/display",function ($req){
   dd($req->age);
});
