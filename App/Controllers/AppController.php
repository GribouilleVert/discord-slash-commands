<?php
namespace App\Controllers;

use App\Utils\Commands\Interaction;
use App\Utils\Commands\InteractionApplicationCommandCallbackData;
use App\Utils\Commands\InteractionResponse;
use App\Utils\DecoderTrait;
use App\Utils\VerificationTrait;
use DI\Container;
use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ServerRequestInterface;
use TypeError;

class AppController {

    use DecoderTrait, VerificationTrait;

    /**
     * @var ClientInterface
     */
    private ClientInterface $httpClient;

    /**
     * @var Container
     */
    private Container $container;

    public function __construct(ClientInterface $httpClient, Container $container)
    {
        $this->httpClient = $httpClient;
        $this->container = $container;
    }

    public function endpoint(ServerRequestInterface $request): array
    {
        $this->checkSignature($request);
        $interactionData = $this->decodeJson($request);

        $response = null;
        switch ($interactionData->type) {
            case Interaction::TYPE_PING:
                $response = new InteractionResponse(InteractionResponse::TYPE_PONG);
                break;

            case Interaction::TYPE_APPLICATION_COMMAND:
                /**
                 * @var $interaction Interaction
                 */
                $interaction = $this->container->make(Interaction::class, [
                    'data' => $interactionData
                ]);

                $callable = $this->resolveCallable($interaction);
                if (is_callable($callable)) {
                    $response = $callable($interaction);
                } else {
                    $response = new InteractionResponse(
                        InteractionResponse::TYPE_MESSAGE,
                        new InteractionApplicationCommandCallbackData('Command not implemented.')
                    );
                }
                break;

            default:
                throw new BadRequestException('Unknown interaction type');
        }

        if (!$response instanceof InteractionResponse) {
            throw new TypeError('Command response of unexpected type, expected ' . InteractionResponse::class . ', got ' . get_class($response) . '.');
        }

        return $response->httpResponse();
    }

    private function resolveCallable(Interaction $interaction): ?callable
    {
        $commands = require 'config/commands.php';
        $callable = $commands[$interaction->getCommandName()]??$commands[$interaction->data->id]??null;

        if (is_string($callable) && strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }

        if (is_array($callable) && isset($callable[0]) && is_object($callable[0])) {
            $callable = [$callable[0], $callable[1]];
        }

        if (is_array($callable) && isset($callable[0]) && is_string($callable[0])) {
            $callable = [$this->container->get($callable[0]), $callable[1]];
        }

        if (is_string($callable) && method_exists($callable, '__invoke')) {
            $callable = $this->container->get($callable);
        }

        return $callable;
    }

}
