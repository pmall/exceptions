<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class UnexpectedValueException extends \UnexpectedValueException
{
    public function __construct(string $source, string $expected, $given, Throwable $previous = null)
    {
        $tpl = $this->tpl($expected);

        $type = new Type($expected, $given);

        $msg = sprintf($tpl, $source, $expected, $type);

        parent::__construct($msg, 0, $previous);
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
