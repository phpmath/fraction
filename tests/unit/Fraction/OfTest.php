<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Fraction;

use PHPMath\BigInteger\BigInteger;
use PHPMath\Fraction\Exception\DivisionByZeroException;
use PHPMath\Fraction\Exception\InvalidFormatException;
use PHPMath\Fraction\Fraction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'toBigInteger')]
final class OfTest extends TestCase
{
    public function testItNormalizesToLowestTerms(): void
    {
        // Act
        $result = Fraction::of(6, 8);

        // Assert
        static::assertSame('3', $result->numerator()->value());
        static::assertSame('4', $result->denominator()->value());
    }

    public function testItMovesTheSignToTheNumerator(): void
    {
        // Act
        $result = Fraction::of(3, -6);

        // Assert
        static::assertSame('-1', $result->numerator()->value());
        static::assertSame('2', $result->denominator()->value());
    }

    public function testItDefaultsTheDenominatorToOne(): void
    {
        // Act
        $result = Fraction::of(5);

        // Assert
        static::assertSame('5', $result->numerator()->value());
        static::assertSame('1', $result->denominator()->value());
    }

    public function testItNormalizesZeroToZeroOverOne(): void
    {
        // Act
        $result = Fraction::of(0, -7);

        // Assert
        static::assertSame('0', $result->numerator()->value());
        static::assertSame('1', $result->denominator()->value());
    }

    public function testItAcceptsBigIntegers(): void
    {
        // Arrange
        $numerator = new BigInteger('100000000000000000000');
        $denominator = new BigInteger('400000000000000000000');

        // Act
        $result = Fraction::of($numerator, $denominator);

        // Assert
        static::assertSame('1/4', (string)$result);
    }

    public function testItAcceptsIntegerStrings(): void
    {
        // Act
        $result = Fraction::of('+10', '-4');

        // Assert
        static::assertSame('-5/2', (string)$result);
    }

    public function testItTreatsLeadingZerosAsDecimal(): void
    {
        // Act
        $result = Fraction::of('010', '0020');

        // Assert
        static::assertSame('1/2', (string)$result);
    }

    public function testItAcceptsNegativeZeroString(): void
    {
        // Act
        $result = Fraction::of('-000');

        // Assert
        static::assertSame('0', (string)$result);
    }

    public function testItThrowsOnZeroDenominator(): void
    {
        // Assert
        $this->expectException(DivisionByZeroException::class);
        $this->expectExceptionMessage('The denominator cannot be zero.');

        // Act
        Fraction::of(1, 0);
    }

    public function testItThrowsOnInvalidNumeratorString(): void
    {
        // Assert
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('Invalid number format: "1.5"');

        // Act
        Fraction::of('1.5');
    }

    public function testItThrowsOnHexadecimalString(): void
    {
        // Assert
        $this->expectException(InvalidFormatException::class);

        // Act
        Fraction::of('0x10');
    }
}
