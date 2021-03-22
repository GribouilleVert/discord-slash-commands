<?php
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

/******************\
|       APP        |
\******************/
$app = new Framework\App;
//---------------------

/*******************\
|      ROUTING      |
|   & MIDDLEWARES   |
\*******************/
$router = $app->getContainer()->get(Framework\Router\Router::class);

$router->addMiddlewares([
    SlashCommands\Factories\ErrorMiddlewareFactory::make($app->getContainer()),
    Framework\Middlewares\HttpsMiddleware::class,
    Framework\Middlewares\TrailingSlashMiddleware::class,
    Framework\Middlewares\MethodDetectorMiddleware::class,
]);

$router->map(
    'POST', '/endpoint', 'integration.endpoint',
    [SlashCommands\Controllers\AppController::class, 'endpoint']
);

//---------------------

/******************\
|    EXECUTION     |
\******************/
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();
$response = $app->run($request, $router);
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

//---------------------
