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

#[CoversMethod(DivisionByZeroException::class, 'zeroToNegativePower')]
final class ZeroToNegativePowerTest extends TestCase
{
    public function testItCreatesTheZeroToNegativePowerException(): void
    {
        // Act
        $result = DivisionByZeroException::zeroToNegativePower();

        // Assert
        static::assertSame('Cannot raise zero to a negative power.', $result->getMessage());
    }
}
