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
use Stringable;

#[CoversMethod(Fraction::class, '__toString')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'isInteger')]
final class ToStringTest extends TestCase
{
    public function testItReturnsTheFractionNotation(): void
    {
        // Arrange
        $fraction = Fraction::of(6, -8);

        // Act
        $result = (string)$fraction;

        // Assert
        static::assertSame('-3/4', $result);
    }

    public function testItReturnsOnlyTheNumeratorForIntegers(): void
    {
        // Arrange
        $fraction = Fraction::of(10, 5);

        // Act
        $result = (string)$fraction;

        // Assert
        static::assertSame('2', $result);
    }

    public function testItIsStringable(): void
    {
        // Arrange
        $fraction = Fraction::of(1, 2);

        // Act
        $result = $fraction;

        // Assert
        static::assertInstanceOf(Stringable::class, $result);
    }
}
