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

#[CoversMethod(Fraction::class, 'abs')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class AbsTest extends TestCase
{
    public function testItReturnsThePositiveValueOfANegativeFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-3/4');

        // Act
        $result = $fraction->abs();

        // Assert
        static::assertSame('3/4', (string)$result);
    }

    public function testItKeepsAPositiveFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('3/4');

        // Act
        $result = $fraction->abs();

        // Assert
        static::assertSame('3/4', (string)$result);
    }
}
