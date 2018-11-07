<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidArgumentException extends \InvalidArgumentException
{
    public function __construct(int $position, string $expected, $given, Throwable $previous = null)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $tpl = $this->tpl($expected);

        $type = new Type($expected, $given);
        $method = new Method($bt[1]);

        $msg = sprintf($tpl, $position, $method, $expected, $type, $bt[1]['file'] ?? '', $bt[1]['line'] ?? '');

        parent::__construct($msg, 0, $previous);
    }

    private function tpl(string $expected): string
    {
        if (interface_exists($expected)) {
            return 'Argument %s passed to %s must implement interface %s, %s given, called in %s on line %s';
        }

        if (class_exists($expected)) {
            return 'Argument %s passed to %s must be an instance of %s, %s given, called in %s on line %s';
        }

        if ($expected == 'callable') {
            return 'Argument %s passed to %s must be %s, %s given, called in %s on line %s';
        }

        return 'Argument %s passed to %s must be of the type %s, %s given, called in %s on line %s';
    }
}
