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

#[CoversMethod(Fraction::class, 'negate')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class NegateTest extends TestCase
{
    public function testItNegatesAPositiveFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/2');

        // Act
        $result = $fraction->negate();

        // Assert
        static::assertSame('-1/2', (string)$result);
    }

    public function testItNegatesANegativeFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-1/2');

        // Act
        $result = $fraction->negate();

        // Assert
        static::assertSame('1/2', (string)$result);
    }

    public function testItKeepsZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->negate();

        // Assert
        static::assertSame('0', (string)$result);
    }
}
