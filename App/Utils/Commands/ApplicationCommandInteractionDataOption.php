<?php
namespace App\Utils\Commands;

class ApplicationCommandInteractionDataOption {

    public const TYPE_VALUE = 0x1;
    public const TYPE_SUBCOMMAND = 0x2;

    public string $name;
    public $value = null;
    public ?ApplicationCommandInteractionDataOptions $options = null;
    public int $type;

    public function __construct(object $data)
    {
        $this->name = $data->name;
        if (property_exists($data, 'value')) {
            $this->value = $data->value;
            $this->type = self::TYPE_VALUE;
        } else {
            if (is_array($data->option??null)) {
                $this->options = new ApplicationCommandInteractionDataOptions($data->option);
            }
            $this->type = self::TYPE_SUBCOMMAND;
        }
    }

}
