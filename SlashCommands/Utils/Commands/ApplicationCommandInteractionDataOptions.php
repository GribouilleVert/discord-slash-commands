<?php
namespace SlashCommands\Utils\Commands;

use ArrayAccess;
use Countable;
use Exception;
use Iterator;

class ApplicationCommandInteractionDataOptions implements Countable, ArrayAccess, Iterator {

    public array $debugOptions = [];
    private array $options = [];
    private array $valuesIndex = [];
    private array $commandsIndex = [];

    private int $index = 0;

    public function __construct(array $options)
    {
        foreach ($options as $i => $_option) {
            $option = new ApplicationCommandInteractionDataOption($_option);
            $this->options[$i] = $option;
            $this->debugOptions[$i] = $_option;
            if ($option->type === ApplicationCommandInteractionDataOption::TYPE_VALUE) {
                $this->valuesIndex[$option->name] = $i;
            } elseif ($option->type === ApplicationCommandInteractionDataOption::TYPE_SUBCOMMAND) {
                $this->commandsIndex[] = $i;
            }
        }
    }

    public function findValue(string $name): ?ApplicationCommandInteractionDataOption
    {
        if (!isset($this->valuesIndex[$name])) {
            return null;
        }
        return $this->options[$this->valuesIndex[$name]];
    }

    public function getSubcommand(): ?ApplicationCommandInteractionDataOption
    {
        if (empty($this->commandsIndex)) {
            return null;
        }
        return $this->options[$this->commandsIndex[0]];
    }

    public function current(): ApplicationCommandInteractionDataOption
    {
        return $this->options[$this->index];
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return array_key_exists($this->index, $this->options);
    }

    public function rewind(): void
    {
        $this->index--;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->valuesIndex);
    }

    public function offsetGet($offset): ?ApplicationCommandInteractionDataOption
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }
        return $this->options[$this->valuesIndex[$offset]];
    }

    public function offsetSet($offset, $value)
    {
        throw new Exception('Options array is read only.');
    }

    public function offsetUnset($offset)
    {
        throw new Exception('Options array is read only.');
    }

    public function count(): int
    {
        return count($this->options);
    }
}
