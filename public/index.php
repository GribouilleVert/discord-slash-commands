<?php
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

if (is_string(SENTRY_DSN)) {
    Sentry\init([
        'dsn' => 'https://8eed2f26c45f4e39b1c02ffd4f6ff219@o342392.ingest.sentry.io/5569077',
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
$strategy = (new App\Strategies\AppStrategy($responseFactory));
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

$router->post('/endpoint', [App\Controllers\AppController::class, 'endpoint']);
$router->get('/endpoint', [App\Controllers\AppController::class, 'endpoint']);

//---------------------

/******************\
|    EXECUTION     |
\******************/
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();
$response = $router->dispatch($request);
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

//---------------------
