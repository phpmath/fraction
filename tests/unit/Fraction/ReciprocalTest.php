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

#[CoversMethod(Fraction::class, 'reciprocal')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'isZero')]
final class ReciprocalTest extends TestCase
{
    public function testItSwapsNumeratorAndDenominator(): void
    {
        // Arrange
        $fraction = Fraction::fromString('3/4');

        // Act
        $result = $fraction->reciprocal();

        // Assert
        static::assertSame('4/3', (string)$result);
    }

    public function testItKeepsThePositiveDenominatorForNegatives(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-3/4');

        // Act
        $result = $fraction->reciprocal();

        // Assert
        static::assertSame('-4/3', (string)$result);
    }

    public function testItInvertsAnInteger(): void
    {
        // Arrange
        $fraction = Fraction::fromString('5');

        // Act
        $result = $fraction->reciprocal();

        // Assert
        static::assertSame('1/5', (string)$result);
    }

    public function testItThrowsForZero(): void
    {
        // Arrange
        $fraction = Fraction::of(0);

        // Assert
        $this->expectException(DivisionByZeroException::class);
        $this->expectExceptionMessage('Cannot divide by zero.');

        // Act
        $fraction->reciprocal();
    }
}
