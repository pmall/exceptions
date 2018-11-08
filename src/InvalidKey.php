<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidKey
{
    private $expected;
    private $values;

    public function __construct(string $expected, array $values)
    {
        $this->expected = $expected;
        $this->values = $values;
    }

    public function __toString()
    {
        $invalid = array_filter($this->values, new TypeFilter($this->expected));

        $key = key($invalid);

        return is_int($key) ? (string) $key : '\'' . $key . '\'';
    }
}
