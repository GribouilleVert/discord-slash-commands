<?php
namespace SlashCommands\Utils\Commands;

use DateTime;
use function Framework\detect_date;

class GuildMember {

    public ?User $user;
    public ?string $nick;
    public array $roles;
    public DateTime $joinedAt;
    public ?DateTime $premiumSince;
    public bool $deaf;
    public bool $mute;
    public ?bool $pending;

    public function __construct(object $data)
    {
        $this->user = $data->user !== null ? new User($data->user): null;
        $this->nick = $data->nick;
        $this->roles = $data->roles;
        $this->joinedAt = detect_date($data->joined_at);
        $this->premiumSince = detect_date($data->premium_since) ?? null;
        $this->deaf = $data->deaf;
        $this->mute = $data->mute;
        $this->pending = $data->pending ?? null;
    }

    public function hasRole(string $snowflake): bool
    {
        return in_array($snowflake, $this->roles);
    }

    public function getDisplayName(): string
    {
        return $this->nick ?? $this->user->username;
    }

}
