<?php

namespace App\Utils\Commands;

class AllowedMentions {

    /**
     * @const Allow all roles mentions, renders $allowedRolesIds construction parameter useless
     */
    public const ROLES_MENTIONS = 'roles';

    /**
     * @const Allow all users mentions, renders $allowedUsersIds construction parameter useless
     */
    public const USERS_MENTIONS = 'users';

    /**
     * @const Allow @everyone and @here
     */
    public const EVERYONE = 'everyone';

    private bool  $repliedUser;
    private array $parse;
    private array $users;
    private array $roles;

    /**
     * AllowedMentions constructor.
     * @param bool $mentionReplies For replies, whether to mention the author of the message being replied to (default false)
     * @param array $allowedTypes An array made of allowed mentions type between: ROLES_MENTIONS, USERS_MENTIONS and EVERYONE
     * @param array $allowedUsersIds Array of user IDs to mention (Max size of 100), ignored if users mentions are allowed
     * @param array $allowedRolesIds Array of role IDs to mention (Max size of 100), ignored if roles mentions are allowed
     */
    public function __construct(
        bool $mentionReplies = false,
        array $allowedTypes = [],
        array $allowedUsersIds = [],
        array $allowedRolesIds = []
    ) {
        $this->repliedUser = $mentionReplies;

        $this->parse = array_filter(
            $allowedTypes,
            fn($item) => is_string($item)
                and in_array($item, [self::USERS_MENTIONS, self::ROLES_MENTIONS, self::EVERYONE])
        );

        if (!in_array(self::USERS_MENTIONS, $this->parse)) {
            $this->users = array_unique(
                array_filter(
                    $allowedUsersIds,
                    fn($item) => is_string($item)
                )
            );
        }

        if (!in_array(self::ROLES_MENTIONS, $this->parse)) {
            $this->roles = array_unique(
                array_filter(
                    $allowedRolesIds,
                    fn($item) => is_string($item)
                )
            );
        }
    }

    /**
     * @return array The serialized object for http response, NOT JSON
     */
    public function serialize(): array
    {
        $result = [
            'parse'        => $this->parse,
            'replied_user' => $this->repliedUser,
        ];

        if (!in_array(self::USERS_MENTIONS, $this->parse)) {
            $result['users'] = $this->users;
        }

        if (!in_array(self::ROLES_MENTIONS, $this->parse)) {
            $result['roles'] = $this->roles;
        }

        return $result;
    }

}
