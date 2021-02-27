<?php
namespace SlashCommands\Utils\Commands\Responses;

use SlashCommands\Utils\Commands\InteractionApplicationCommandCallbackData;
use SlashCommands\Utils\Commands\InteractionResponse;
use Exception;
use const JSON_PRETTY_PRINT;

class JsonResponse extends InteractionResponse {

    public function __construct($object, int $type = InteractionResponse::TYPE_MESSAGE)
    {
        if (!in_array($type, [InteractionResponse::TYPE_MESSAGE_WITH_SOURCE, InteractionResponse::TYPE_MESSAGE])) {
            throw new Exception('Json response type must either be message with or without source');
        }

        parent::__construct(
            $type,
            new InteractionApplicationCommandCallbackData(
                "```json\n" . json_encode($object, JSON_PRETTY_PRINT) . "\n```",
                null, false, [], true
            )
        );
    }

}
