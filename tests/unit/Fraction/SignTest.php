<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Fraction;

use PHPMath\BigInteger\Sign;
use PHPMath\Fraction\Fraction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Fraction::class, 'sign')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class SignTest extends TestCase
{
    public function testItIsPositive(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/2');

        // Act
        $result = $fraction->sign();

        // Assert
        static::assertSame(Sign::Positive, $result);
    }

    public function testItIsNegative(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-1/2');

        // Act
        $result = $fraction->sign();

        // Assert
        static::assertSame(Sign::Negative, $result);
    }

    public function testItIsZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->sign();

        // Assert
        static::assertSame(Sign::Zero, $result);
    }
}
