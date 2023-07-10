<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    protected function jsonResponse(
        Response $response,
        string $status,
        $message,
        int $code
    ): Response {
        $result = [
            'code'    => $code,
            'status'  => $status,
            'message' => $message
        ];
        
        $payload = json_encode($result);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}