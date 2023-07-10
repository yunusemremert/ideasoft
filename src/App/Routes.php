<?php

use Slim\Routing\RouteCollectorProxy;

return static function ($app) {
    $app->get('/', \App\Controller\DefaultController::class . ':index');

    // Version 1
    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        // orders
        $group->get('/orders', \App\Controller\Order\OrderController::class . ':getAll');
        $group->post('/orders', \App\Controller\Order\OrderController::class . ':add');
        $group->delete('/orders/order/{id:[0-9]+}', \App\Controller\Order\OrderController::class . ':delete');

        // products
        $group->get('/products', \App\Controller\Product\ProductController::class . ':getAll');

        // customers
        $group->get('/customers', \App\Controller\Customer\CustomerController::class . ':getAll');

        // discount
        $group->get('/discount/calculate/{id:[0-9]+}', \App\Controller\Discount\DiscountController::class . ':calculate');
    });

    return $app;
};