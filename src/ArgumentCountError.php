<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArgumentCountError extends \ArgumentCountError
{
    public function __construct(int $expected, int $given, Throwable $previous = null)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $tpl = 'Too few arguments to function %s, %s passed in %s on line %s and exactly %s expected';

        $method = new Method($bt[1]);

        $msg = sprintf($tpl, $method, $given, $bt[1]['file'], $bt[1]['line'], $expected);

        parent::__construct($msg, 0, $previous);
    }
}
