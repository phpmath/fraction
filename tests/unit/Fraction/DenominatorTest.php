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

#[CoversMethod(Fraction::class, 'denominator')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class DenominatorTest extends TestCase
{
    public function testItReturnsThePositiveDenominator(): void
    {
        // Arrange
        $fraction = Fraction::of(6, -8);

        // Act
        $result = $fraction->denominator();

        // Assert
        static::assertSame('4', $result->value());
    }
}
