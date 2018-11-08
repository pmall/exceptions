<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class TypeFilter
{
    private $expected;

    public function __construct(string $expected)
    {
        $this->expected = $expected;
    }

    public function __invoke($value)
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
