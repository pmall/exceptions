<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class IsTypedAs
{
    /**
     * The expected type.
     *
     * Can be an interface name, a class name, 'callable' or a value returned by
     * gettype().
     *
     * @var string
     */
    private $type;

    /**
     * Constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Return whether the given value is typed as expected.
     *
     * @param mixed $value
     * @return bool
     */
    public function __invoke($value): bool
    {
        if (interface_exists($this->type) || class_exists($this->type)) {
            return $value instanceof $this->type;
        }

        if ($this->type == 'callable') {
            return is_callable($value);
        }

        return strtolower($this->type) == strtolower(gettype($value));
    }
}
