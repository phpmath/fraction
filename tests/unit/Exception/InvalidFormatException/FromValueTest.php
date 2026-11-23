<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Exception\InvalidFormatException;

use PHPMath\Fraction\Exception\InvalidFormatException;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(InvalidFormatException::class, 'fromValue')]
final class FromValueTest extends TestCase
{
    public function testItIncludesTheValueInTheMessage(): void
    {
        // Act
        $result = InvalidFormatException::fromValue('abc');

        // Assert
        static::assertSame('Invalid number format: "abc"', $result->getMessage());
    }
}
