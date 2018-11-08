<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class UnexpectedArrayValueException extends \UnexpectedValueException
{
    public function __construct(string $source, string $expected, array $values)
    {
        $tpl = $this->tpl($expected);

        $xs[] = $source;
        $xs[] = $expected;
        $xs[] = new InvalidType($expected, $values);
        $xs[] = new InvalidKey($expected, $values);

        parent::__construct(vsprintf($tpl, $xs));
    }

    private function tpl(string $expected): string
    {
        if (interface_exists($expected)) {
            return 'Return value of %s must be an array containing only objects implementing interface %s, %s returned for key %s';
        }

        if (class_exists($expected)) {
            return 'Return value of %s must be an array containing only instances of %s, %s returned for key %s';
        }

        if ($expected == 'callable') {
            return 'Return value of %s must be an array containing only %s values, %s returned for key %s';
        }

        return 'Return value of %s must be an array containing only values of the type %s, %s returned for key %s';
    }
}
