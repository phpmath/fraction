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

#[CoversMethod(Fraction::class, 'compareTo')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class CompareToTest extends TestCase
{
    public function testItReturnsMinusOneWhenSmaller(): void
    {
        // Arrange
        $left = Fraction::fromString('1/3');
        $right = Fraction::fromString('1/2');

        // Act
        $result = $left->compareTo($right);

        // Assert
        static::assertSame(-1, $result);
    }

    public function testItReturnsOneWhenGreater(): void
    {
        // Arrange
        $left = Fraction::fromString('2/3');
        $right = Fraction::fromString('1/2');

        // Act
        $result = $left->compareTo($right);

        // Assert
        static::assertSame(1, $result);
    }

    public function testItReturnsZeroWhenEqual(): void
    {
        // Arrange
        $left = Fraction::fromString('2/4');
        $right = Fraction::fromString('1/2');

        // Act
        $result = $left->compareTo($right);

        // Assert
        static::assertSame(0, $result);
    }

    public function testItComparesNegativeNumbers(): void
    {
        // Arrange
        $left = Fraction::fromString('-1/2');
        $right = Fraction::fromString('-1/3');

        // Act
        $result = $left->compareTo($right);

        // Assert
        static::assertSame(-1, $result);
    }

    public function testItComparesAcrossSigns(): void
    {
        // Arrange
        $left = Fraction::fromString('-5');
        $right = Fraction::fromString('1/100');

        // Act
        $result = $left->compareTo($right);

        // Assert
        static::assertSame(-1, $result);
    }
}
