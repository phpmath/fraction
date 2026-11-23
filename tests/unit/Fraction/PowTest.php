<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Fraction;

use PHPMath\Fraction\Exception\DivisionByZeroException;
use PHPMath\Fraction\Fraction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Fraction::class, 'pow')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'isZero')]
#[CoversMethod(Fraction::class, 'reciprocal')]
final class PowTest extends TestCase
{
    public function testItRaisesToAPositivePower(): void
    {
        // Arrange
        $fraction = Fraction::fromString('2/3');

        // Act
        $result = $fraction->pow(3);

        // Assert
        static::assertSame('8/27', (string)$result);
    }

    public function testItReturnsOneForExponentZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('2/3');

        // Act
        $result = $fraction->pow(0);

        // Assert
        static::assertSame('1', (string)$result);
    }

    public function testItReturnsOneForZeroToThePowerZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->pow(0);

        // Assert
        static::assertSame('1', (string)$result);
    }

    public function testItHandlesNegativeExponents(): void
    {
        // Arrange
        $fraction = Fraction::fromString('2/3');

        // Act
        $result = $fraction->pow(-2);

        // Assert
        static::assertSame('9/4', (string)$result);
    }

    public function testItKeepsTheSignForOddNegativeExponents(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-2/3');

        // Act
        $result = $fraction->pow(-3);

        // Assert
        static::assertSame('-27/8', (string)$result);
    }

    public function testItDropsTheSignForEvenPowers(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-2/3');

        // Act
        $result = $fraction->pow(2);

        // Assert
        static::assertSame('4/9', (string)$result);
    }

    public function testItKeepsZeroForPositiveExponents(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->pow(5);

        // Assert
        static::assertSame('0', (string)$result);
    }

    public function testItThrowsForZeroToANegativePower(): void
    {
        // Arrange
        $fraction = Fraction::of(0);

        // Assert
        $this->expectException(DivisionByZeroException::class);
        $this->expectExceptionMessage('Cannot raise zero to a negative power.');

        // Act
        $fraction->pow(-1);
    }
}
