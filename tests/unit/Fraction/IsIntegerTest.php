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

#[CoversMethod(Fraction::class, 'isInteger')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class IsIntegerTest extends TestCase
{
    public function testItIsTrueForWholeNumbers(): void
    {
        // Arrange
        $fraction = Fraction::fromString('6/3');

        // Act
        $result = $fraction->isInteger();

        // Assert
        static::assertTrue($result);
    }

    public function testItIsTrueForZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->isInteger();

        // Assert
        static::assertTrue($result);
    }

    public function testItIsFalseForProperFractions(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/2');

        // Act
        $result = $fraction->isInteger();

        // Assert
        static::assertFalse($result);
    }
}
