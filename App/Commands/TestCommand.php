<?php
namespace App\Commands;

use SlashCommands\Utils\Commands\Interaction;
use SlashCommands\Utils\Commands\InteractionApplicationCommandCallbackData;
use SlashCommands\Utils\Commands\InteractionResponse;

class TestCommand {

    public function test(Interaction $interaction): InteractionResponse
    {
        return new InteractionResponse(
            InteractionResponse::TYPE_MESSAGE_WITH_SOURCE,
            new InteractionApplicationCommandCallbackData('It works !')
        );
    }

}
