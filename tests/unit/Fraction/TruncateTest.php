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

#[CoversMethod(Fraction::class, 'truncate')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class TruncateTest extends TestCase
{
    public function testItHandlesPositiveFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('7/2');

        // Act
        $result = $fraction->truncate();

        // Assert
        static::assertSame('3', $result->value());
    }

    public function testItHandlesNegativeFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-7/2');

        // Act
        $result = $fraction->truncate();

        // Assert
        static::assertSame('-3', $result->value());
    }

    public function testItHandlesInteger(): void
    {
        // Arrange
        $fraction = Fraction::fromString('4');

        // Act
        $result = $fraction->truncate();

        // Assert
        static::assertSame('4', $result->value());
    }

    public function testItHandlesZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->truncate();

        // Assert
        static::assertSame('0', $result->value());
    }

    public function testItHasNoMinusSignWhenANegativeFractionTruncatesToZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-1/2');

        // Act
        $result = $fraction->truncate();

        // Assert
        static::assertSame('0', $result->value());
    }
}
