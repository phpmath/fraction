<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Exception;

use InvalidArgumentException;

use function sprintf;

/**
 * Thrown when a string is not a valid integer, fraction or decimal number.
 */
final class InvalidFormatException extends InvalidArgumentException implements FractionException
{
    /**
     * Creates an exception for the given invalid value.
     *
     * @param string $value The invalid value.
     */
    public static function fromValue(string $value): self
    {
        return new self(sprintf('Invalid number format: "%s"', $value));
    }
}
