<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class TypedAs
{
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function __invoke($value)
    {
        if (interface_exists($this->type) || class_exists($this->type)) {
            return $value instanceof $this->type;
        }

        if ($this->type == 'callable') {
            return is_callable($value);
        }

        return gettype($value) == $this->type;
    }
}
