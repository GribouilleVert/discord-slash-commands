<?php
namespace SlashCommands\Utils\Commands;

class User {

    public string $id;
    public string $username;
    public string $discriminator;
    public ?string $avatar;
    public bool $bot;

    public function __construct(object $data)
    {
        $this->id = $data->id;
        $this->username = $data->username;
        $this->discriminator = $data->discriminator;
        $this->avatar = $data->avatar;
        $this->bot = $data->bot??false;
    }

    public function getFullTag(): string
    {
        return $this->username . '#' . $this->discriminator;
    }

    public function getAvatarUrl(): string
    {
        if ($this->avatar AND str_starts_with('a_', $this->avatar)) {
            $url = "avatars/{$this->id}/{$this->avatar}.gif";
        } elseif ($this->avatar) {
            $url = "avatars/{$this->id}/{$this->avatar}.webp";
        } else {
            $defaultId = (int)$this->discriminator % 5;
            $url = "embed/avatars/{$defaultId}.png";
        }

        return "https://cdn.discordapp.com/$url";
    }

}
