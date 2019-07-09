<?php
use Maxi\Router;
use Maxi\Request;

Router::get("/home/:id/:name", function ($req, $params) {
    // echo $req->get('name');
    echo "<pre>";
    print_r($params);
});

Router::post("/home", function ($req) {
    echo $req->get('name');
    // echo "<pre>";
    // print_r($req->postAll());
});
Router::get("/login", function ($req) {
    echo $req->get('name');
});
Router::get("/login/:id", function ($req) {
    echo $req->get('name');
});


Router::get("/register", function ($req) {
  //  echo $req->get('name');
  $res->title ="";
  $res->type = "";
  
  return " 
  <html>  
  <head>
  <title> Maxi's routing system Sign up Page </title>
  </head>
  <body>

  </body>

  </html>
  
  ";
});
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
});