<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class UnexpectedArrayValueException extends \UnexpectedValueException
{
    public function __construct(string $source, string $expected, array $values, Throwable $previous = null)
    {
        $tpl = $this->tpl($expected);

        $invalid = new InvalidArray($expected, $values);
        [$key, $type] = $invalid->first();

        $msg = sprintf($tpl, $source, $expected, $type, $key);

        parent::__construct($msg, 0, $previous);
    }

    private function tpl(string $expected): string
    {
        if (interface_exists($expected)) {
            return 'Return value of %s must be an array containing only objects implementing interface %s, %s returned for key %s';
        }

        if (class_exists($expected)) {
            return 'Return value of %s must be an array containing only instances of %s, %s returned for key %s';
        }

        return 'Return value of %s must be an array containing only values of the type %s, %s returned for key %s';
    }
}
