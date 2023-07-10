<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require __DIR__ . '/../../vendor/autoload.php';

$container = new Container();

AppFactory::setContainer($container);
$app       = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Monolog
$logger          = new Logger('app');
$streamHandler   = new StreamHandler(__DIR__ . '/../../var/log', 100);

$logger->pushHandler($streamHandler);

$errorMiddleware = $app->addErrorMiddleware(true, true, true, $logger);

require_once __DIR__ . '/Services.php';
(require_once __DIR__ . '/Routes.php')($app);