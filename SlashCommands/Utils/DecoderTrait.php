<?php
namespace SlashCommands\Utils;

use Psr\Http\Message\ServerRequestInterface;
use const JSON_ERROR_NONE;

trait DecoderTrait {

    private function decodeJson(ServerRequestInterface $request): ?object
    {
        $rawJson = (string)$request->getBody();

        $json = json_decode($rawJson);
        return json_last_error() !== JSON_ERROR_NONE ? null : $json;
    }

}
