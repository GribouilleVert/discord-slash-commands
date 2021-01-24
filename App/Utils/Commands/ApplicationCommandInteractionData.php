<?php
namespace App\Utils\Commands;

class ApplicationCommandInteractionData {

    public string $id;
    public string $name;
    public ApplicationCommandInteractionDataOptions $options;

    public function __construct(object $data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->options = new ApplicationCommandInteractionDataOptions($data->options ?? []);
    }

}
