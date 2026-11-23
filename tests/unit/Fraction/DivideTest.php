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

#[CoversMethod(Fraction::class, 'divide')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'multiply')]
#[CoversMethod(Fraction::class, 'reciprocal')]
#[CoversMethod(Fraction::class, 'isZero')]
final class DivideTest extends TestCase
{
    public function testItDividesFractions(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('1/4');

        // Act
        $result = $left->divide($right);

        // Assert
        static::assertSame('2', (string)$result);
    }

    public function testItDividesByANegative(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('-1/4');

        // Act
        $result = $left->divide($right);

        // Assert
        static::assertSame('-2', (string)$result);
    }

    public function testItDividesZeroByAFraction(): void
    {
        // Arrange
        $left = Fraction::fromString('0');
        $right = Fraction::fromString('5/7');

        // Act
        $result = $left->divide($right);

        // Assert
        static::assertSame('0', (string)$result);
    }

    public function testItThrowsOnZeroDivisor(): void
    {
        // Arrange
        $left = Fraction::of(1, 2);
        $right = Fraction::of(0);

        // Assert
        $this->expectException(DivisionByZeroException::class);
        $this->expectExceptionMessage('Cannot divide by zero.');

        // Act
        $left->divide($right);
    }
}
