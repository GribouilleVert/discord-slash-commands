<?php
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

if (is_string(SENTRY_DSN)) {
    Sentry\init([
        'dsn' => SENTRY_DSN,
        'capture_silenced_errors' => true,
    ]);
}

/******************\
|    CONTAINER     |
\******************/
$container = (new Framework\Factories\ContainerFactory)();

$staticInstancier = $container->get(Framework\Utils\StaticInstancier::class);
$staticInstancier->initClass(Framework\Database\Sprinkler::class);
//---------------------

$responseFactory = new Laminas\Diactoros\ResponseFactory;
$strategy = (new SlashCommands\Strategies\EndpointStrategy($responseFactory));
$strategy->setContainer($container);
$router   = (new League\Route\Router);
$router->setStrategy($strategy);

/*******************\
|      ROUTING      |
|   & MIDDLEWARES   |
\*******************/
$router->middlewares(Framework\array_resolve([
    Framework\Middlewares\HttpsMiddleware::class,
    Framework\Middlewares\TralingSlashMiddleware::class,
    Framework\Middlewares\MethodDetectorMiddleware::class,
], $container));

$router->post('/endpoint', [SlashCommands\Controllers\AppController::class, 'endpoint']);

//---------------------

/******************\
|    EXECUTION     |
\******************/
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();
$response = $router->dispatch($request);
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

//---------------------
