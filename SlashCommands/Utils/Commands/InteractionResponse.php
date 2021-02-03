<?php
namespace SlashCommands\Utils\Commands;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class InteractionResponse {

    /**
     * @const In response to a ACK request
     */
    public const TYPE_PONG = 0x1;

    /**
     * @const Acknowledge the reception without sending a response
     */
    public const TYPE_ACKNOWLEDGE = 0x2;

    /**
     * @const Sends back a message
     */
    public const TYPE_MESSAGE = 0x3;

    /**
     * @const Sends back a message and display the input
     */
    public const TYPE_MESSAGE_WITH_SOURCE = 0x4;

    /**
     * @const Display the user input back
     */
    public const TYPE_ACKNOWLEDGE_WITH_SOURCE = 0x5;

    public int $type;
    private ?InteractionApplicationCommandCallbackData $data;

    public function __construct(int $type, ?InteractionApplicationCommandCallbackData $data = null)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return ResponseInterface The serialized object for http response
     */
    public function httpResponse(): ResponseInterface
    {
        $_ = ['type' => $this->type];
        if ($this->data !== null) $_['data'] = $this->data->serialize();
        return new JsonResponse($_, 200);
    }

}
