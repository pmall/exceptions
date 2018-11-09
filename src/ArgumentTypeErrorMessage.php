<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArgumentTypeErrorMessage
{
    private static $testing = false;

    private $bt;
    private $position;
    private $expected;
    private $given;

    public static function testing()
    {
        self::$testing = true;
    }

    public function __construct(int $position, string $expected, $given)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $this->bt = $bt[1];
        $this->position = $position;
        $this->expected = $expected;
        $this->given = $given;
    }

    public function __toString()
    {
        $xs[] = $this->position;
        if (! self::$testing) $xs[] = new Method($this->bt);
        $xs[] = $this->expected;
        $xs[] = new Type($this->expected, $this->given);
        if (! self::$testing) $xs[] = $this->bt['file'];
        if (! self::$testing) $xs[] = $this->bt['line'];

        return vsprintf($this->tpl(), $xs);
    }

    private function tpl(): string
    {
        if (interface_exists($this->expected)) {
            return self::$testing
                ? 'Argument %s passed to x must implement interface %s, %s given'
                : 'Argument %s passed to %s must implement interface %s, %s given, called in %s on line %s';
        }

        if (class_exists($this->expected)) {
            return self::$testing
                ? 'Argument %s passed to x must be an instance of %s, %s given'
                : 'Argument %s passed to %s must be an instance of %s, %s given, called in %s on line %s';
        }

        if ($this->expected == 'callable') {
            return self::$testing
                ? 'Argument %s passed to x must be %s, %s given'
                : 'Argument %s passed to %s must be %s, %s given, called in %s on line %s';
        }

        return self::$testing
            ? 'Argument %s passed to x must be of the type %s, %s given'
            : 'Argument %s passed to %s must be of the type %s, %s given, called in %s on line %s';
    }
}
