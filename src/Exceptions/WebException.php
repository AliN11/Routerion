<?php namespace Routerion\Exceptions;

use Routerion\Contracts\ExceptionsInterface;

class WebException implements ExceptionsInterface
{

    /**
     * Show not found response
     * @return void
     */
    public function notFound()
    {
        echo '<h1>404 Not Found</h1>';
        header('HTTP/1.0 404 Not Found');
    }
}