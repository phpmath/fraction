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

#[CoversMethod(Fraction::class, 'round')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class RoundTest extends TestCase
{
    public function testItHandlesPositiveHalfAwayFromZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('5/2');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('3', $result->value());
    }

    public function testItHandlesNegativeHalfAwayFromZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-5/2');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('-3', $result->value());
    }

    public function testItHandlesRoundDown(): void
    {
        // Arrange
        $fraction = Fraction::fromString('7/3');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('2', $result->value());
    }

    public function testItHandlesRoundUp(): void
    {
        // Arrange
        $fraction = Fraction::fromString('8/3');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('3', $result->value());
    }

    public function testItHandlesNegativeRoundDown(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-7/3');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('-2', $result->value());
    }

    public function testItHandlesJustBelowHalf(): void
    {
        // Arrange
        $fraction = Fraction::fromString('49/100');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('0', $result->value());
    }

    public function testItHandlesNegativeJustBelowHalf(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-49/100');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('0', $result->value());
    }

    public function testItHandlesInteger(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-4');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('-4', $result->value());
    }

    public function testItHandlesZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('0', $result->value());
    }

    public function testItHasNoMinusSignWhenANegativeFractionRoundsToZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-1/3');

        // Act
        $result = $fraction->round();

        // Assert
        static::assertSame('0', $result->value());
    }
}
