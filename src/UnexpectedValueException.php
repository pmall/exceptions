<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class UnexpectedValueException extends \UnexpectedValueException
{
    public function __construct(string $source, string $expected, $given)
    {
        $tpl = $this->tpl($expected);

        $xs[] = $source;
        $xs[] = $expected;
        $xs[] = new Type($expected, $given);

        parent::__construct(vsprintf($tpl, $xs));
    }

    private function tpl(string $expected): string
    {
        if (interface_exists($expected)) {
            return 'Return value of %s must implement interface %s, %s returned';
        }

        if (class_exists($expected)) {
            return 'Return value of %s must be an instance of %s, %s returned';
        }

        if ($expected == 'callable') {
            return 'Return value of %s must be %s, %s returned';
        }

        return 'Return value of %s must be of the type %s, %s returned';
    }
}
