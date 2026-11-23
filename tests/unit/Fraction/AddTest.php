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

#[CoversMethod(Fraction::class, 'add')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class AddTest extends TestCase
{
    public function testItAddsFractionsWithDifferentDenominators(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('1/3');

        // Act
        $result = $left->add($right);

        // Assert
        static::assertSame('5/6', (string)$result);
    }

    public function testItReducesTheResult(): void
    {
        // Arrange
        $left = Fraction::fromString('1/6');
        $right = Fraction::fromString('1/3');

        // Act
        $result = $left->add($right);

        // Assert
        static::assertSame('1/2', (string)$result);
    }

    public function testItAddsNegativeNumbers(): void
    {
        // Arrange
        $left = Fraction::fromString('-1/2');
        $right = Fraction::fromString('-1/3');

        // Act
        $result = $left->add($right);

        // Assert
        static::assertSame('-5/6', (string)$result);
    }

    public function testItReturnsAnIntegerWhenTheSumIsWhole(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('1/2');

        // Act
        $result = $left->add($right);

        // Assert
        static::assertSame('1', (string)$result);
    }

    public function testItReturnsZeroForOpposites(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('-1/2');

        // Act
        $result = $left->add($right);

        // Assert
        static::assertSame('0', (string)$result);
    }
}
