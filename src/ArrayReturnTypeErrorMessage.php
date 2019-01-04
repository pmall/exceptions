<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArrayReturnTypeErrorMessage
{
    /**
     * The source of the array containing a value not typed as expected.
     *
     * @var string
     */
    private $source;

    /**
     * The expected type.
     *
     * @see \Quanta\Exceptions\IsTypedAs
     *
     * @var string
     */
    private $type;

    /**
     * The array containing a value not typed as expected.
     *
     * @var array
     */
    private $values;

    /**
     * Constructor.
     *
     * @param string    $source
     * @param string    $type
     * @param array     $values
     */
    public function __construct(string $source, string $type, array $values)
    {
        $this->source = $source;
        $this->type = $type;
        $this->values = $values;
    }

    /**
     * Return the error message.
     *
     * @return string
     */
    public function __toString()
    {
        $xs[] = $this->source;
        $xs[] = $this->type;
        $xs[] = new InvalidType($this->type, $this->values);
        $xs[] = new InvalidKey($this->type, $this->values);

        return vsprintf($this->tpl(), $xs);
    }

    /**
     * Return the template of the error message accoring to the expected type.
     *
     * @return string
     */
    private function tpl(): string
    {
        if (interface_exists($this->type)) {
            return 'Return value of %s must be an array of objects implementing interface %s, %s returned for key %s';
        }

        if (class_exists($this->type)) {
            return 'Return value of %s must be an array of %s instances, %s returned for key %s';
        }

        return 'Return value of %s must be an array of %s values, %s returned for key %s';
    }
}
