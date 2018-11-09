<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArrayArgumentTypeErrorMessage
{
    private static $testing = false;

    private $bt;
    private $position;
    private $expected;
    private $values;

    public static function testing()
    {
        self::$testing = true;
    }

    public function __construct(int $position, string $expected, array $values)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $this->bt = $bt[1];
        $this->position = $position;
        $this->expected = $expected;
        $this->values = $values;
    }

    public function __toString()
    {
        $xs[] = $this->position;
        if (! self::$testing) $xs[] = new Method($this->bt);
        $xs[] = $this->expected;
        $xs[] = new InvalidType($this->expected, $this->values);
        $xs[] = new InvalidKey($this->expected, $this->values);
        if (! self::$testing) $xs[] = $this->bt['file'];
        if (! self::$testing) $xs[] = $this->bt['line'];

        return vsprintf($this->tpl(), $xs);
    }

    private function tpl(): string
    {
        if (interface_exists($this->expected)) {
            return self::$testing
                ? 'Argument %s passed to x must be an array of objects implementing interface %s, %s given for key %s'
                : 'Argument %s passed to %s must be an array of objects implementing interface %s, %s given for key %s, called in %s on line %s';
        }

        if (class_exists($this->expected)) {
            return self::$testing
                ? 'Argument %s passed to x must be an array of %s instances, %s given for key %s'
                : 'Argument %s passed to %s must be an array of %s instances, %s given for key %s, called in %s on line %s';
        }

        return self::$testing
            ? 'Argument %s passed to x must be an array of %s values, %s given for key %s'
            : 'Argument %s passed to %s must be an array of %s values, %s given for key %s, called in %s on line %s';
    }
}
