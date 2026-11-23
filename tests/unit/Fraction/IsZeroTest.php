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

#[CoversMethod(Fraction::class, 'isZero')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class IsZeroTest extends TestCase
{
    public function testItIsTrueForZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0/5');

        // Act
        $result = $fraction->isZero();

        // Assert
        static::assertTrue($result);
    }

    public function testItIsFalseForNonZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/5');

        // Act
        $result = $fraction->isZero();

        // Assert
        static::assertFalse($result);
    }
}
