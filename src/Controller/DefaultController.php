<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DefaultController extends BaseController
{
    private const API_VERSION = '1.0.0';

    public function index(Request $request, Response $response, $args) : Response {
        $url = $_SERVER['HTTP_HOST'];

        $endpoints = [
            'orders'    => $url . '/api/v1/orders',
            'products'  => $url . '/api/v1/products',
            'customers' => $url . '/api/v1/customers'
        ];

        $message = [
            'endpoints' => $endpoints,
            'version'   => self::API_VERSION,
            'timestamp' => time(),
        ];

        return $this->jsonResponse($response, 'success', $message, 200);
    }
}