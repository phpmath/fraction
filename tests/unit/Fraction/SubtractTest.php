<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Fraction;

use PHPMath\Fraction\Fraction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Fraction::class, 'subtract')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'add')]
#[CoversMethod(Fraction::class, 'negate')]
final class SubtractTest extends TestCase
{
    public function testItSubtractsFractions(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('1/3');

        // Act
        $result = $left->subtract($right);

        // Assert
        static::assertSame('1/6', (string)$result);
    }

    public function testItCanReturnANegativeResult(): void
    {
        // Arrange
        $left = Fraction::fromString('1/3');
        $right = Fraction::fromString('1/2');

        // Act
        $result = $left->subtract($right);

        // Assert
        static::assertSame('-1/6', (string)$result);
    }

    public function testItReturnsZeroForEqualValues(): void
    {
        // Arrange
        $left = Fraction::fromString('3/4');
        $right = Fraction::fromString('3/4');

        // Act
        $result = $left->subtract($right);

        // Assert
        static::assertSame('0', (string)$result);
    }
}
