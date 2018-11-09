<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ReturnTypeErrorMessage
{
    private $source;
    private $expected;
    private $given;

    public function __construct(string $source, string $expected, $given)
    {
        $this->source = $source;
        $this->expected = $expected;
        $this->given = $given;
    }

    public function __toString()
    {
        $xs[] = $this->source;
        $xs[] = $this->expected;
        $xs[] = new Type($this->expected, $this->given);

        return vsprintf($this->tpl(), $xs);
    }

    private function tpl(): string
    {
        if (interface_exists($this->expected)) {
            return 'Return value of %s must implement interface %s, %s returned';
        }

        if (class_exists($this->expected)) {
            return 'Return value of %s must be an instance of %s, %s returned';
        }

        if ($this->expected == 'callable') {
            return 'Return value of %s must be %s, %s returned';
        }

        return 'Return value of %s must be of the type %s, %s returned';
    }
}
