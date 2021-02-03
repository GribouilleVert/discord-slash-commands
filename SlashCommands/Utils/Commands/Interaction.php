<?php
namespace SlashCommands\Utils\Commands;

use Exception;

class Interaction {

    public const TYPE_PING = 0x1; //ACK Type
    public const TYPE_APPLICATION_COMMAND = 0x2; //User Command;

    public string $id;
    public int $type;
    public ?ApplicationCommandInteractionData $data;
    public ?string $guildId;
    public ?string $channelId;
    public ?GuildMember $member;
    private string $token;
    public int $version;

    public function __construct(object $data)
    {
        $this->id = $data->id;
        $this->type = $data->type;
        $this->data = isset($data->data) ? new ApplicationCommandInteractionData($data->data) : null;
        $this->guildId = $data->guild_id;
        $this->channelId = $data->channel_id;
        $this->member = $data->member !== null ? new GuildMember($data->member) : null;
        $this->token = $data->token;
        $this->version = $data->version;
    }

    /**
     * @return string Le nom de la commande tapée par l'utilisateur
     * @throws Exception Si l'interaction n'es pas une commande
     */
    public function getCommandName(): string
    {
        if ($this->type !== self::TYPE_APPLICATION_COMMAND)
            throw new Exception("This interaction is not a command");

        return $this->data->name;
    }

    /**
     * @return null|ApplicationCommandInteractionDataOptions
     * @throws Exception Si l'interaction n'es pas une commande
     */
    public function getOptions(): ?ApplicationCommandInteractionDataOptions
    {
        if ($this->type !== self::TYPE_APPLICATION_COMMAND)
            throw new Exception("This interaction is not a command");

        return $this->data->options;
    }

}
