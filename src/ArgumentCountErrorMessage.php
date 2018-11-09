<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArgumentCountErrorMessage
{
    private static $testing = false;

    private $bt;
    private $expected;
    private $given;

    public static function testing()
    {
        self::$testing = true;
    }

    public function __construct(int $expected, int $given)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $this->bt = $bt[1];
        $this->expected = $expected;
        $this->given = $given;
    }

    public function __toString()
    {
        if (! self::$testing) $xs[] = new Method($this->bt);
        $xs[] = $this->given;
        if (! self::$testing) $xs[] = $this->bt['file'];
        if (! self::$testing) $xs[] = $this->bt['line'];
        $xs[] = $this->expected;

        return vsprintf($this->tpl(), $xs);
    }

    private function tpl(): string
    {
        return self::$testing
            ? 'Too few arguments to function x, %s passed and exactly %s expected'
            : 'Too few arguments to function %s, %s passed in %s on line %s and exactly %s expected';
    }
}
