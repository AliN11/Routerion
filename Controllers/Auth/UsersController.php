<?php

namespace Controllers\Auth;


class UsersController {

    public function index()
    {
        echo 'Users Page';
    }


    public function getUserInfo($id)
    {
        echo $id;
    }



    public function single($name, $age = null)
    {
        $age = !empty($age) ? $age : 'not provided';
        echo "Your name is $name and your age is $age";
    }
}