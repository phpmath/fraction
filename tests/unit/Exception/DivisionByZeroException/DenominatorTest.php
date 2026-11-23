<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Exception\DivisionByZeroException;

use PHPMath\Fraction\Exception\DivisionByZeroException;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(DivisionByZeroException::class, 'denominator')]
final class DenominatorTest extends TestCase
{
    public function testItCreatesTheDenominatorException(): void
    {
        // Act
        $result = DivisionByZeroException::denominator();

        // Assert
        static::assertSame('The denominator cannot be zero.', $result->getMessage());
    }
}
