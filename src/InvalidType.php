<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidType
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

        $value = current($invalid);

        return (string) new Type($this->expected, $value);
    }
}
