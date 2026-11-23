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

#[CoversMethod(DivisionByZeroException::class, 'divisor')]
final class DivisorTest extends TestCase
{
    public function testItCreatesTheDivisorException(): void
    {
        // Act
        $result = DivisionByZeroException::divisor();

        // Assert
        static::assertSame('Cannot divide by zero.', $result->getMessage());
    }
}
