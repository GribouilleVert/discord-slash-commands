<?php
namespace App\Utils\Commands;

class ApplicationCommandInteractionData {

    public string $id;
    public string $name;
    public ?ApplicationCommandInteractionDataOptions $options = null;

    public function __construct(object $data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        if (is_array($data->options ?? null)) {
            $this->options = new ApplicationCommandInteractionDataOptions($data->options);
        }
    }

}
