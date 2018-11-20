<?php declare(strict_types=1);

namespace Quanta\Exceptions;

trait ArrayTypeCheckTrait
{
    /**
     * Return whether all the values of the given array match the given type.
     *
     * @param array     $values
     * @param string    $type
     * @return bool
     */
    private function areAllTypedAs(array $values, string $type): bool
    {
        return (new Every(new TypedAs($type)))($values);
    }
}
