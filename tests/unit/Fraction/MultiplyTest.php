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

#[CoversMethod(Fraction::class, 'multiply')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class MultiplyTest extends TestCase
{
    public function testItMultipliesFractions(): void
    {
        // Arrange
        $left = Fraction::fromString('2/3');
        $right = Fraction::fromString('3/4');

        // Act
        $result = $left->multiply($right);

        // Assert
        static::assertSame('1/2', (string)$result);
    }

    public function testItMultipliesByZero(): void
    {
        // Arrange
        $left = Fraction::fromString('2/3');
        $right = Fraction::fromString('0');

        // Act
        $result = $left->multiply($right);

        // Assert
        static::assertSame('0', (string)$result);
    }

    public function testItMultipliesNegatives(): void
    {
        // Arrange
        $left = Fraction::fromString('-2/3');
        $right = Fraction::fromString('-3/4');

        // Act
        $result = $left->multiply($right);

        // Assert
        static::assertSame('1/2', (string)$result);
    }

    public function testItKeepsTheSignOfMixedOperands(): void
    {
        // Arrange
        $left = Fraction::fromString('-2/3');
        $right = Fraction::fromString('3/4');

        // Act
        $result = $left->multiply($right);

        // Assert
        static::assertSame('-1/2', (string)$result);
    }
}
