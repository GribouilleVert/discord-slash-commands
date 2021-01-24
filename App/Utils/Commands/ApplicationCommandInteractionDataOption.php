<?php
namespace App\Utils\Commands;

use Exception;

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
            if (is_array($data->options??null)) {
                $this->options = new ApplicationCommandInteractionDataOptions($data->options);
            }
            $this->type = self::TYPE_SUBCOMMAND;
        }
    }

    /**
     * @return ApplicationCommandInteractionDataOptions|null
     * @throws Exception
     */
    public function getOptions(): ?ApplicationCommandInteractionDataOptions
    {
        if ($this->type !== self::TYPE_SUBCOMMAND) {
            throw new Exception('Only sub commands can have sub options');
        }
        return $this->options;
    }

}
