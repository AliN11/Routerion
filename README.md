# Routerion - PHP Router

A simple, fast and powerful PHP router

* Define routes easily
* Dynamic route parameters
* Supports various HTTP methods
* REST API Support

## Installation

1. You can install Routerion with composer:

```$ composer require alin11/routerion```


2. Create a `.htaccess` file on the root directory:

```htaccess
Options +FollowSymLinks
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} (.+)/$
RewriteRule ^ %1 [L,R=301]

RewriteRule ^(.*)$ index.php [NC,L]
```

3. Create `config.php` file or add following `PHP Constants` to your configuration file:

```php
<?php
define('CONTROLLER_PATH', __DIR__ . '\Controllers');
define('CONTROLLER_NAMESPACE', '\Controllers');
```

## Usage

Let's define our routes. To define routes create a `index.php` file on the root directory if not exists, or create a `routes.php` file and include it into `index.php` file:

```php
<?php

require 'config.php';
require __DIR__.'/vendor/autoload.php';

$route = new Routerion\Route(new Routerion\Exceptions\APIException);


$route -> get('/', 'HomeController@index');
$route -> get('/users', 'Auth\UsersController@index');
$route -> get('/users/{name}/{age?}', 'Auth\UsersController@single');

$route -> post('/get-user-info/{id}', 'Auth\UsersController@getUserInfo');

$route -> get('closure', function(){
  echo 'This is closure';
});


$route -> get('closure-with-parameters/{required}/{optional?}', function($required, $optional = null){
  echo $required;
  echo $optional;
});

$route -> run();
```


### Controllers

Controller files are located in `Controllers` directory. Edit `config.php` constants if you have different controllers directory or namespace.


## License

MIT Licensed: <http://www.opensource.org/licenses/MIT>