<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArrayArgumentTypeErrorMessage
{
    /**
     * Whether the testing mode is enabled or not.
     *
     * When testing mode is enabled the backtrace information are not added to
     * the error message.
     *
     * This allows to compare exceptions when testing.
     *
     * @var bool
     */
    private static $testing = false;

    /**
     * The backtrace.
     *
     * @var array
     */
    private $bt;

    /**
     * The argument position.
     *
     * @var int
     */
    private $position;

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
     * Enable the testing mode.
     *
     * @return void
     */
    public static function testing()
    {
        self::$testing = true;
    }

    /**
     * Constructor.
     *
     * @param int       $position
     * @param string    $type
     * @param array     $values
     */
    public function __construct(int $position, string $type, array $values)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $this->bt = $bt[1];
        $this->position = $position;
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
        $xs[] = $this->position;
        if (! self::$testing) $xs[] = $this->method();
        $xs[] = $this->type;
        $xs[] = new InvalidType($this->type, $this->values);
        $xs[] = new InvalidKey($this->type, $this->values);
        if (! self::$testing) $xs[] = $this->bt['file'];
        if (! self::$testing) $xs[] = $this->bt['line'];

        return vsprintf($this->tpl(), $xs);
    }

    /**
     * Return a string representation of the called function from the backtrace.
     *
     * @return string
     */
    private function method(): string
    {
        return isset($this->bt['class'])
            ? sprintf('%s::%s()', $this->bt['class'], $this->bt['function'])
            : $this->bt['function'];
    }

    /**
     * Return the template of the error message accoring to the expected type
     * and the testing mode.
     *
     * @return string
     */
    private function tpl(): string
    {
        if (interface_exists($this->type)) {
            return self::$testing
                ? 'Argument %s passed to x must be an array of objects implementing interface %s, %s given for key %s'
                : 'Argument %s passed to %s must be an array of objects implementing interface %s, %s given for key %s, called in %s on line %s';
        }

        if (class_exists($this->type)) {
            return self::$testing
                ? 'Argument %s passed to x must be an array of %s instances, %s given for key %s'
                : 'Argument %s passed to %s must be an array of %s instances, %s given for key %s, called in %s on line %s';
        }

        return self::$testing
            ? 'Argument %s passed to x must be an array of %s values, %s given for key %s'
            : 'Argument %s passed to %s must be an array of %s values, %s given for key %s, called in %s on line %s';
    }
}
