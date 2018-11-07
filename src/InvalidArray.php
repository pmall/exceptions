<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidArray
{
    private $expected;
    private $values;

    public function __construct(string $expected, array $values)
    {
        $this->expected = $expected;
        $this->values = $values;
    }

    public function first(): array
    {
        $invalid = array_filter($this->values, [$this, 'filtered']);

        $key = is_int($tmp = key($invalid)) ? $tmp : '\'' . $tmp . '\'';
        $value = current($invalid);

        return [$key, new Type($this->expected, $value)];
    }

    private function filtered($value): bool
    {
        if (interface_exists($this->expected) || class_exists($this->expected)) {
            return ! $value instanceof $this->expected;
        }

        if ($this->expected == 'callable') {
            return ! is_callable($value);
        }

        return gettype($value) !== $this->expected;
    }
}
