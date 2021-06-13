<?php
namespace SlashCommands\Utils\Commands;

class ApplicationCommandInteractionData {

    public string $id;
    public ?string $name;
    public ApplicationCommandInteractionDataOptions $options;
    public ?int $componentType;

    public function __construct(object $data)
    {
        $this->id = $data->id??$data->custom_id;
        $this->name = $data->name??null;
        $this->options = new ApplicationCommandInteractionDataOptions($data->options ?? []);
        $this->componentType = $data->component_type??null;
    }

}
