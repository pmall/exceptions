<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidArgumentException extends \InvalidArgumentException
{
    private static $testing = false;

    public static function testing()
    {
        self::$testing = true;
    }

    public function __construct(int $position, string $expected, $given)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $tpl = $this->tpl($expected);

        $xs[] = $position;
        if (! self::$testing) $xs[] = new Method($bt[1]);
        $xs[] = $expected;
        $xs[] = new Type($expected, $given);
        if (! self::$testing) $xs[] = $bt[1]['file'];
        if (! self::$testing) $xs[] = $bt[1]['line'];

        parent::__construct(vsprintf($tpl, $xs));
    }

    private function tpl(string $expected): string
    {
        if (interface_exists($expected)) {
            return self::$testing
                ? 'Argument %s passed to x must implement interface %s, %s given'
                : 'Argument %s passed to %s must implement interface %s, %s given, called in %s on line %s';
        }

        if (class_exists($expected)) {
            return self::$testing
                ? 'Argument %s passed to x must be an instance of %s, %s given'
                : 'Argument %s passed to %s must be an instance of %s, %s given, called in %s on line %s';
        }

        if ($expected == 'callable') {
            return self::$testing
                ? 'Argument %s passed to x must be %s, %s given'
                : 'Argument %s passed to %s must be %s, %s given, called in %s on line %s';
        }

        return self::$testing
            ? 'Argument %s passed to x must be of the type %s, %s given'
            : 'Argument %s passed to %s must be of the type %s, %s given, called in %s on line %s';
    }
}
