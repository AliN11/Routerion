<?php namespace Routerion\Exceptions;

use Routerion\Contracts\ExceptionsInterface;

class APIException implements ExceptionsInterface
{

    /**
     * Show not found response
     * @return void
     */
    public function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/json');
        echo json_encode([
            'error' => '404 Not Found'
        ]);
    }
}