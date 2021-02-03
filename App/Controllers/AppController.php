<?php
namespace App\Controllers;

use App\Utils\Commands\ApplicationCommandInteractionDataOption;
use App\Utils\Commands\Interaction;
use App\Utils\Commands\InteractionApplicationCommandCallbackData;
use App\Utils\Commands\InteractionResponse;
use App\Utils\DecoderTrait;
use App\Utils\VerificationTrait;
use DI\Container;
use Exception;
use GuzzleHttp\Psr7\Uri;
use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
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

    public function endpoint(ServerRequestInterface $request): ResponseInterface
    {
        if (is_string(REQUEST_CATCHER_URL)) {
            $this->httpClient->sendRequest($request->withUri(new Uri(REQUEST_CATCHER_URL)));
        }

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
                        new InteractionApplicationCommandCallbackData('Command not implemented.', null, false, [], true)
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

    private function resolveCallable($command, ?array $subHandlerSection = null): ?callable
    {
        $commands = ($subHandlerSection ?? require 'config/commands.php');
        if ($command instanceof Interaction) {
            $callable = $commands[$command->getCommandName()]??$commands[$command->data->id]??null;
            $subCommand = $command->getOptions()->getSubcommand();
        } elseif ($command instanceof ApplicationCommandInteractionDataOption) {
            $callable = $commands[$command->name]??null;
            $subCommand = $command->options !== null ? $command->options->getSubcommand() : null;
        } else {
            throw new Exception('Unable to resolve command: command type is neither a command nor a subcommand');
        }

        if (is_object($subCommand) AND is_array($callable) && array_key_exists($subCommand->name, $callable)) {
            return $this->resolveCallable($subCommand, $callable);
        }

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

        if (!is_callable($callable)) {
            return null;
        }

        return $callable;
    }

}
