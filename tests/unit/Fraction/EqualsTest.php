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

#[CoversMethod(Fraction::class, 'equals')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class EqualsTest extends TestCase
{
    public function testItIsEqualForTheSameValueInDifferentForms(): void
    {
        // Arrange
        $left = Fraction::fromString('2/4');
        $right = Fraction::fromString('1/2');

        // Act
        $result = $left->equals($right);

        // Assert
        static::assertTrue($result);
    }

    public function testItIsNotEqualForDifferentValues(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('1/3');

        // Act
        $result = $left->equals($right);

        // Assert
        static::assertFalse($result);
    }

    public function testItIsNotEqualForOpposites(): void
    {
        // Arrange
        $left = Fraction::fromString('1/2');
        $right = Fraction::fromString('-1/2');

        // Act
        $result = $left->equals($right);

        // Assert
        static::assertFalse($result);
    }
}
