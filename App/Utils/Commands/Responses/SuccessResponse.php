<?php
namespace App\Utils\Commands\Responses;

use App\Utils\Commands\InteractionApplicationCommandCallbackData;
use App\Utils\Commands\InteractionResponse;
use Exception;

class SuccessResponse extends InteractionResponse {

    public function __construct(string $success, int $type = InteractionResponse::TYPE_MESSAGE)
    {
        if (!in_array($type, [InteractionResponse::TYPE_MESSAGE_WITH_SOURCE, InteractionResponse::TYPE_MESSAGE])) {
            throw new Exception('Success response type must either be message with or without source');
        }

        parent::__construct(
            $type,
            new InteractionApplicationCommandCallbackData(
                ':white_check_mark: ' . $success,
                null, false, [], true
            )
        );
    }

}
