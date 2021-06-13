<?php
namespace SlashCommands\Utils\Commands;

use DateTime;
use function Framework\detect_date;

class Message {

    public array $attachments;
    public User $author;
    public string $channelId;
    public ?array $components;
    public string $content;
    public ?DateTime $editedTimestamp;
    public array $embeds;
    public int $flags;
    public string $messageId;
    public bool $mentionedEveryone;
    public array $mentionedRoles;
    public array $mentions;
    public bool $pinned;
    public ?DateTime $timestamp;
    public bool $tts;
    public int $type;

    public function __construct(object $data)
    {
        $this->attachments = $data->attachments;
        $this->author = new User($data->author);
        $this->channelId = $data->channel_id;
        $this->components = $data->components;
        $this->content = $data->content;
        $this->editedTimestamp = detect_date($data->edited_timestamp);
        $this->mentionedEveryone = $data->mention_everyone;
        $this->mentionedRoles = $data->mention_roles;
        $this->mentions = $data->mentions;
        $this->pinned = $data->pinned;
        $this->timestamp = detect_date($data->timestamp);
        $this->tts = $data->tts;
        $this->type = $data->type;

    }

}