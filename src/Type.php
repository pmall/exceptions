<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class Type
{
    /**
     * The expected type.
     *
     * Allows to return a better string representation when it is and interface
     * name or a class name.
     *
     * @var string
     */
    private $type;

    /**
     * The value we want the type represented as a string.
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string    $type
     * @param mixed     $value
     */
    public function __construct(string $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Return a string representation of the value's type according to the
     * expected type.
     *
     * @return string
     */
    public function __toString()
    {
        $type = gettype($this->value);

        return $type === 'object' && (interface_exists($this->type) || class_exists($this->type))
            ? 'instance of ' . get_class($this->value)
            : $type;
    }
}
