<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidKey
{
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
     * @param string    $type
     * @param array     $values
     */
    public function __construct(string $type, array $values)
    {
        $this->type = $type;
        $this->values = $values;
    }

    /**
     * Return a string representation of the key associated to the first value
     * not typed as expected.
     *
     * @return string
     */
    public function __toString()
    {
        $valid = array_filter($this->values, new IsTypedAs($this->type));

        $invalid = array_diff_key($this->values, $valid);

        if (count($invalid) > 0) {
            $key = key($invalid);

            return is_int($key) ? (string) $key : '\'' . $key . '\'';
        }

        return '';
    }
}
