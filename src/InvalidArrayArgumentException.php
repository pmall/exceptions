<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class InvalidArrayArgumentException extends \InvalidArgumentException
{
    public function __construct(int $position, string $expected, array $values, Throwable $previous = null)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $tpl = $this->tpl($expected);

        $method = new Method($bt[1]);
        $invalid = new InvalidArray($expected, $values);
        [$key, $type] = $invalid->first();

        $msg = sprintf($tpl, $position, $method, $expected, $type, $key, $bt[1]['file'], $bt[1]['line']);

        parent::__construct($msg, 0, $previous);
    }

    private function tpl(string $expected): string
    {
        if (interface_exists($expected)) {
            return 'Argument %s passed to %s must be an array containing only objects implementing interface %s, %s given for key %s, called in %s on line %s';
        }

        if (class_exists($expected)) {
            return 'Argument %s passed to %s must be an array containing only instances of %s, %s given for key %s, called in %s on line %s';
        }

        if ($expected == 'callable') {
            return 'Argument %s passed to %s must be an array containing only %s values, %s given for key %s, called in %s on line %s';
        }

        return 'Argument %s passed to %s must be an array containing only values of the type %s, %s given for key %s, called in %s on line %s';
    }
}
