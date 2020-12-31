<?php
namespace App\Utils\Commands;

class ApplicationCommandInteractionDataOption {

    public const TYPE_VALUE = 0x1;
    public const TYPE_SUBGROUP = 0x2;

    public string $name;
    public ?string $value = null;
    public ?array $options = null;
    public int $type;

    public function __construct(object $data)
    {
        $this->name = $data->name;
        if (is_array($data->options ?? null)) {
            $this->options = array_map(
                fn(object $option) => new self($option),
                $data->options
            );
            $this->type = self::TYPE_SUBGROUP;
        } else {
            $this->value = $data->value;
            $this->type = self::TYPE_VALUE;
        }
    }

}
