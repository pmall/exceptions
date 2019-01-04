<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ReturnTypeErrorMessage
{
    /**
     * The source of the value not typed as expected.
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
     * The value not typed as expected.
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string    $source
     * @param string    $type
     * @param mixed     $value
     */
    public function __construct(string $source, string $type, $value)
    {
        $this->source = $source;
        $this->type = $type;
        $this->value = $value;
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
        $xs[] = new Type($this->type, $this->value);

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
            return 'Return value of %s must implement interface %s, %s returned';
        }

        if (class_exists($this->type)) {
            return 'Return value of %s must be an instance of %s, %s returned';
        }

        if ($this->type == 'callable') {
            return 'Return value of %s must be %s, %s returned';
        }

        return 'Return value of %s must be of the type %s, %s returned';
    }
}
