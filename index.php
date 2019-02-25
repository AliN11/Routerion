<?php

require 'config.php';
require __DIR__.'/vendor/autoload.php';

$route = new Routerion\Route(new Routerion\Exceptions\APIException);


$route->get('/', 'HomeController@index');
$route->get('/users', 'Auth\UsersController@index');
$route->get('/users/{name}/{age?}', 'Auth\UsersController@single');

$route->post('/get-user-info/{id}', 'Auth\UsersController@getUserInfo');

$route->get('closure', function(){
  echo 'This is closure';
});


$route->get('closure-with-parameters/{required}/{optional?}', function($required, $optional = null){
  echo $required;
  echo $optional;
});

$route->run();