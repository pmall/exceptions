<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class Type
{
    private $expected;
    private $value;

    public function __construct(string $expected, $value)
    {
        $this->expected = $expected;
        $this->value = $value;
    }

    public function __toString()
    {
        $type = gettype($this->value);

        return $type === 'object' && (interface_exists($this->expected) || class_exists($this->expected))
            ? 'instance of ' . get_class($this->value)
            : $type;
    }
}
