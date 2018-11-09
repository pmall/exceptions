<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class ArrayReturnTypeErrorMessage
{
    private $source;
    private $expected;
    private $values;

    public function __construct(string $source, string $expected, array $values)
    {
        $this->source = $source;
        $this->expected = $expected;
        $this->values = $values;
    }

    public function __toString()
    {
        $xs[] = $this->source;
        $xs[] = $this->expected;
        $xs[] = new InvalidType($this->expected, $this->values);
        $xs[] = new InvalidKey($this->expected, $this->values);

        return vsprintf($this->tpl(), $xs);
    }

    private function tpl(): string
    {
        if (interface_exists($this->expected)) {
            return 'Return value of %s must be an array of objects implementing interface %s, %s returned for key %s';
        }

        if (class_exists($this->expected)) {
            return 'Return value of %s must be an array of %s instances, %s returned for key %s';
        }

        return 'Return value of %s must be an array of %s values, %s returned for key %s';
    }
}
