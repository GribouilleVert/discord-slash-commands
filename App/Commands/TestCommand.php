<?php
namespace App\Commands;

use App\Utils\Commands\Interaction;
use App\Utils\Commands\InteractionApplicationCommandCallbackData;
use App\Utils\Commands\InteractionResponse;

class TestCommand {

    public function test(Interaction $interaction): InteractionResponse
    {
        return new InteractionResponse(
            InteractionResponse::TYPE_MESSAGE_WITH_SOURCE,
            new InteractionApplicationCommandCallbackData('It works !')
        );
    }

}
