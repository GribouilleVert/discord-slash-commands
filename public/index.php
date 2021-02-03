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
Framework\Factories\StaticInstancierFactory::init($container);
//---------------------

/*******************\
|      ROUTING      |
|   & MIDDLEWARES   |
\*******************/
$router = new League\Route\Router;

$strategy = $container->get('app.strategy');
$strategy->setContainer($container);
$router->setStrategy($strategy);

$router->post('/endpoint', [SlashCommands\Controllers\AppController::class, 'endpoint'])
    ->setStrategy($container->get(SlashCommands\Strategies\EndpointStrategy::class));

//---------------------

/******************\
|    EXECUTION     |
\******************/
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();
$response = $router->dispatch($request);
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

//---------------------
