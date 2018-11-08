<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidArrayArgumentException extends \InvalidArgumentException
{
    private static $testing = false;

    public static function testing()
    {
        self::$testing = true;
    }

    public function __construct(int $position, string $expected, array $values)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $tpl = $this->tpl($expected);

        $xs[] = $position;
        if (! self::$testing) $xs[] = new Method($bt[1]);
        $xs[] = $expected;
        $xs[] = new InvalidType($expected, $values);
        $xs[] = new InvalidKey($expected, $values);
        if (! self::$testing) $xs[] = $bt[1]['file'];
        if (! self::$testing) $xs[] = $bt[1]['line'];

        parent::__construct(vsprintf($tpl, $xs));
    }

    private function tpl(string $expected): string
    {
        if (interface_exists($expected)) {
            return self::$testing
                ? 'Argument %s passed to x must be an array of objects implementing interface %s, %s given for key %s'
                : 'Argument %s passed to %s must be an array of objects implementing interface %s, %s given for key %s, called in %s on line %s';
        }

        if (class_exists($expected)) {
            return self::$testing
                ? 'Argument %s passed to x must be an array of %s instances, %s given for key %s'
                : 'Argument %s passed to %s must be an array of %s instances, %s given for key %s, called in %s on line %s';
        }

        return self::$testing
            ? 'Argument %s passed to x must be an array of %s values, %s given for key %s'
            : 'Argument %s passed to %s must be an array of %s values, %s given for key %s, called in %s on line %s';
    }
}
