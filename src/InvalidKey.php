<?php declare(strict_types=1);

namespace Quanta\Exceptions;

use Quanta\Callbacks\Not;

final class InvalidKey
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
        $invalid = array_filter($this->values, new Not(new TypedAs($this->type)));

        $key = key($invalid);

        return is_int($key) ? (string) $key : '\'' . $key . '\'';
    }
}
