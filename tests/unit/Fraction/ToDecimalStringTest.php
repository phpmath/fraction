<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Fraction;

use PHPMath\Fraction\Exception\InvalidScaleException;
use PHPMath\Fraction\Fraction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Fraction::class, 'toDecimalString')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'multiply')]
#[CoversMethod(Fraction::class, 'round')]
final class ToDecimalStringTest extends TestCase
{
    public function testItFormatsAnExactDecimal(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/8');

        // Act
        $result = $fraction->toDecimalString(3);

        // Assert
        static::assertSame('0.125', $result);
    }

    public function testItPadsWithZeros(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/2');

        // Act
        $result = $fraction->toDecimalString(4);

        // Assert
        static::assertSame('0.5000', $result);
    }

    public function testItFormatsAnInteger(): void
    {
        // Arrange
        $fraction = Fraction::fromString('3');

        // Act
        $result = $fraction->toDecimalString(2);

        // Assert
        static::assertSame('3.00', $result);
    }

    public function testItFormatsScaleZeroWithoutPoint(): void
    {
        // Arrange
        $fraction = Fraction::fromString('7/2');

        // Act
        $result = $fraction->toDecimalString(0);

        // Assert
        static::assertSame('4', $result);
    }

    public function testItRoundsHalfAwayFromZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('2/3');

        // Act
        $result = $fraction->toDecimalString(2);

        // Assert
        static::assertSame('0.67', $result);
    }

    public function testItRoundsNegativeHalfAwayFromZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-1/8');

        // Act
        $result = $fraction->toDecimalString(2);

        // Assert
        static::assertSame('-0.13', $result);
    }

    public function testItFormatsNegativeValues(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-5/4');

        // Act
        $result = $fraction->toDecimalString(2);

        // Assert
        static::assertSame('-1.25', $result);
    }

    public function testItDoesNotPrintNegativeZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-1/1000');

        // Act
        $result = $fraction->toDecimalString(2);

        // Assert
        static::assertSame('0.00', $result);
    }

    public function testItKeepsLeadingZerosAfterThePoint(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/100');

        // Act
        $result = $fraction->toDecimalString(2);

        // Assert
        static::assertSame('0.01', $result);
    }

    public function testItFormatsLargeValues(): void
    {
        // Arrange
        $fraction = Fraction::fromString('123456789012345678901234567890/7');

        // Act
        $result = $fraction->toDecimalString(3);

        // Assert
        static::assertSame('17636684144620811271604938270.000', $result);
    }

    public function testItThrowsForANegativeScale(): void
    {
        // Arrange
        $fraction = Fraction::of(1, 2);

        // Assert
        $this->expectException(InvalidScaleException::class);
        $this->expectExceptionMessage('The scale must be zero or greater, -1 given.');

        // Act
        $fraction->toDecimalString(-1);
    }
}
