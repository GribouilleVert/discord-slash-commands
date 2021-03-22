<?php
namespace SlashCommands\Factories;

use Framework\Guard\AuthenticationInterface;
use Laminas\Diactoros\Response;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Middlewares\Whoops;
use Psr\Container\ContainerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorMiddlewareFactory {

    public static function make(ContainerInterface $container)
    {
        if (!$container->has('app.sentryDsn') OR !$container->has('env') OR !$container->has('release')) {
            die('Missing required container entry for error handler.');
        }

        \Sentry\init([
            'dsn' => $container->get('app.sentryDsn'),
            'capture_silenced_errors' => true,
            'environment' => $container->get('env'),
            'release' => $container->get('release'),
        ]);

        if ($container->has(AuthenticationInterface::class)) {
            $authentification = $container->get(AuthenticationInterface::class);
            if ($authentification->isLogged()) {
                $user = $authentification->getUser();
                \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($user): void {
                    $scope->setUser([
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'email' => $user->email,
                    ]);
                });
            }
        }

        $whoops = new Run();
        $whoops->appendHandler(function (\Throwable $error) {
            \Sentry\captureException($error);
        });
        if ($container->get('env') !== 'production') {
            $whoops->appendHandler(new PrettyPageHandler);
        } else {
            $whoops->appendHandler(function () use ($container) {
                self::productionErrorHandler($container);
            });
        }

        $whoops->register();

        return new Whoops($whoops);
    }

    public static function productionErrorHandler(ContainerInterface $container): void
    {
        $response = new Response('Une erreur fatale est survenue.', 500);

        (new SapiEmitter)->emit($response);
        die;
    }

}