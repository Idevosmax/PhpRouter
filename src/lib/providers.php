<?php

// use \Maxi\Request;
  $classes  = [
    "Request" => new Maxi\Request
  ];

  $alliases = [
      "Request" => $classes['Request']
  ];

  define("Request",$alliases["Request"]);