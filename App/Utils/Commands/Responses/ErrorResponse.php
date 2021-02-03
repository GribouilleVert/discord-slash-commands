<?php
namespace App\Utils\Commands\Responses;

use App\Utils\Commands\InteractionApplicationCommandCallbackData;
use App\Utils\Commands\InteractionResponse;
use Exception;

class ErrorResponse extends InteractionResponse {

    public function __construct(string $error, int $type = InteractionResponse::TYPE_MESSAGE)
    {
        if (!in_array($type, [InteractionResponse::TYPE_MESSAGE_WITH_SOURCE, InteractionResponse::TYPE_MESSAGE])) {
            throw new Exception('Error response type must either be message with or without source');
        }

        parent::__construct(
            $type,
            new InteractionApplicationCommandCallbackData(
                ':x: ' . $error,
                null, false, [], true
            )
        );
    }

}
