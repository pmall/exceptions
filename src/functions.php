<?php declare(strict_types=1);

namespace Quanta\Exceptions;

/**
 * Return whether all the values of the given array are typed as the given type.
 *
 * @param string    $type
 * @param array     $values
 * @return bool
 */
function areAllTypedAs(string $type, array $values): bool
{
    return count($values) == count(array_filter($values, new IsTypedAs($type)));
}
