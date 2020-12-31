<?php
namespace App\Utils\Commands;

class InteractionApplicationCommandCallbackData {

    private string $content;
    private AllowedMentions $allowedMentions;
    private bool $tts;
    private array $embeds;

    /**
     * InteractionApplicationCommandCallbackData constructor.
     * @param string $content The message
     * @param AllowedMentions|null $allowedMentions The mentions allowed in the message
     * @param bool $tts If the message should be read with text to speech
     * @param array $embeds Embeds objects, up to 10, see https://discord.com/developers/docs/resources/channel#embed-object
     */
    public function __construct(
        string $content,
        ?AllowedMentions $allowedMentions = null,
        bool $tts = false,
        array $embeds = []
    ) {
        $this->content = $content;
        $this->allowedMentions = $allowedMentions ?? new AllowedMentions();
        $this->tts = $tts;
        $this->embeds = $embeds;
    }

    /**
     * @return array The serialized object for http response, NOT JSON
     */
    public function serialize(): array
    {
        return [
            'tts' => $this->tts,
            'content' => $this->content,
            'embeds' => $this->embeds,
            'allowed_mentions' => $this->allowedMentions->serialize()
        ];
    }

}
