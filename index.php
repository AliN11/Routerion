<?php

include 'config.php';
include 'Router.php';

$route = new Routerion\Router;


$route -> get('/', 'HomeController@index');
$route -> get('/users', 'Auth\UsersController@index');
$route -> put('/get-user-info/{id}', 'Auth\UsersController@getUserInfo');
$route -> get('/users/{name}/{age?}', 'Auth\UsersController@single');


$route -> get('closure', function(){
  echo 'This is closure';
});


$route -> get('closure-with-parameters/{required}/{optional?}', function($required, $optional){
  echo $required;
  echo $optional;
});

$route -> run();