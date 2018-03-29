# Routerion - PHP Router

A simple and and powerful PHP router

* Define routes easily
* Dynamic route parameters
* Supports various HTTP methods

## Installition

1. You can install Routerion with composer:

```$ composer require alin11/routerion```


2. Create a `.htaccess` file on the root directory:

```htaccess

Options +FollowSymLinks
RewriteEngine On
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

include 'config.php';
require __DIR__.'/vendor/autoload.php';

$route = new Routerion\Route;


$route -> get('/', 'HomeController@index');
$route -> get('/users', 'Auth\UsersController@index');
$route -> put('/get-user-info/{id}', 'Auth\UsersController@getUserInfo');
$route -> get('/users/{name}/{age?}', 'Auth\UsersController@single');


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

Controller files are located in `Controllers` directory. Edit `config.php` file if you have different controllers directory.


## License

MIT Licensed, <http://www.opensource.org/licenses/MIT>