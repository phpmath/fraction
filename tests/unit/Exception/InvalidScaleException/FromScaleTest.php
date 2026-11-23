<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Exception\InvalidScaleException;

use PHPMath\Fraction\Exception\InvalidScaleException;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(InvalidScaleException::class, 'fromScale')]
final class FromScaleTest extends TestCase
{
    public function testItIncludesTheScaleInTheMessage(): void
    {
        // Act
        $result = InvalidScaleException::fromScale(-3);

        // Assert
        static::assertSame('The scale must be zero or greater, -3 given.', $result->getMessage());
    }
}
