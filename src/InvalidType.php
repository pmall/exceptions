<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidType
{
    private $type;
    private $values;

    public function __construct(string $type, array $values)
    {
        $this->type = $type;
        $this->values = $values;
    }

    public function __toString()
    {
        $valid = array_filter($this->values, new TypeFilter($this->type));

        $invalid = array_diff_key($this->values, $valid);

        $value = current($invalid);

        return (string) new Type($this->type, $value);
    }
}
