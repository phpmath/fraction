<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Exception;

use InvalidArgumentException;

/**
 * Thrown when a zero denominator or divisor is given.
 */
final class DivisionByZeroException extends InvalidArgumentException implements FractionException
{
    /**
     * Creates an exception for a zero denominator.
     */
    public static function denominator(): self
    {
        return new self('The denominator cannot be zero.');
    }

    /**
     * Creates an exception for a zero divisor.
     */
    public static function divisor(): self
    {
        return new self('Cannot divide by zero.');
    }

    /**
     * Creates an exception for raising zero to a negative power.
     */
    public static function zeroToNegativePower(): self
    {
        return new self('Cannot raise zero to a negative power.');
    }
}
