<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArgumentCountError extends \ArgumentCountError
{
    private static $testing = false;

    public static function testing()
    {
        self::$testing = true;
    }

    public function __construct(int $expected, int $given)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $tpl = self::$testing
            ? 'Too few arguments to function x, %s passed and exactly %s expected'
            : 'Too few arguments to function %s, %s passed in %s on line %s and exactly %s expected';

        if (! self::$testing) $xs[] = new Method($bt[1]);
        $xs[] = $given;
        if (! self::$testing) $xs[] = $bt[1]['file'];
        if (! self::$testing) $xs[] = $bt[1]['line'];
        $xs[] = $expected;

        parent::__construct(vsprintf($tpl, $xs));
    }
}
