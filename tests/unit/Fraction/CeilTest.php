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

#[CoversMethod(Fraction::class, 'ceil')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'floor')]
#[CoversMethod(Fraction::class, 'negate')]
#[CoversMethod(Fraction::class, 'isInteger')]
final class CeilTest extends TestCase
{
    public function testItHandlesPositiveFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('7/2');

        // Act
        $result = $fraction->ceil();

        // Assert
        static::assertSame('4', $result->value());
    }

    public function testItHandlesNegativeFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-7/2');

        // Act
        $result = $fraction->ceil();

        // Assert
        static::assertSame('-3', $result->value());
    }

    public function testItHandlesInteger(): void
    {
        // Arrange
        $fraction = Fraction::fromString('4');

        // Act
        $result = $fraction->ceil();

        // Assert
        static::assertSame('4', $result->value());
    }

    public function testItHandlesNegativeInteger(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-4');

        // Act
        $result = $fraction->ceil();

        // Assert
        static::assertSame('-4', $result->value());
    }

    public function testItHandlesZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->ceil();

        // Assert
        static::assertSame('0', $result->value());
    }

    public function testItHandlesSmallPositiveFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/3');

        // Act
        $result = $fraction->ceil();

        // Assert
        static::assertSame('1', $result->value());
    }

    public function testItHasNoMinusSignWhenANegativeFractionCeilsToZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-1/2');

        // Act
        $result = $fraction->ceil();

        // Assert
        static::assertSame('0', $result->value());
    }
}
