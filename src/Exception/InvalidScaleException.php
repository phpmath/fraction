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
 * Thrown when a scale is negative.
 */
final class InvalidScaleException extends InvalidArgumentException implements FractionException
{
    /**
     * Creates an exception for the given invalid scale.
     *
     * @param int $scale The invalid scale.
     */
    public static function fromScale(int $scale): self
    {
        return new self(sprintf('The scale must be zero or greater, %d given.', $scale));
    }
}
