<?php declare(strict_types=1);

namespace Quanta\Exceptions;

final class Method
{
    private $bt;

    public function __construct(array $bt)
    {
        $this->bt = $bt;
    }

    public function __toString()
    {
        return isset($this->bt['class'])
            ? sprintf('%s::%s()', $this->bt['class'], $this->bt['function'])
            : $this->bt['function'];
    }
}
