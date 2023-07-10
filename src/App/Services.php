<?php

global $container;
$container->set('orderService', function () {
    return new \App\Service\Order\OrderService();
});

$container->set('productService', function () {
    return new \App\Service\Product\ProductService();
});

$container->set('customerService', function () {
    return new \App\Service\Customer\CustomerService();
});