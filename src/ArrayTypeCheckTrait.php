<?php declare(strict_types=1);

namespace Quanta\Exceptions;

trait ArrayTypeCheckTrait
{
    /**
     * Return whether all the values of the given array match the given type.
     *
     * @param string    $type
     * @param array     $values
     * @return bool
     */
    private function areAllTypedAs(string $type, array $values): bool
    {
        return count($values) == count(array_filter($values, new TypedAs($type)));
    }
}
