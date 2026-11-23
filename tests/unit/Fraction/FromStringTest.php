<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Fraction;

use PHPMath\Fraction\Exception\DivisionByZeroException;
use PHPMath\Fraction\Exception\InvalidFormatException;
use PHPMath\Fraction\Fraction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Fraction::class, 'fromString')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
#[CoversMethod(Fraction::class, 'toBigInteger')]
final class FromStringTest extends TestCase
{
    public function testItParsesAFraction(): void
    {
        // Act
        $result = Fraction::fromString('3/4');

        // Assert
        static::assertSame('3/4', (string)$result);
    }

    public function testItNormalizesAFraction(): void
    {
        // Act
        $result = Fraction::fromString('6/8');

        // Assert
        static::assertSame('3/4', (string)$result);
    }

    public function testItParsesANegativeFraction(): void
    {
        // Act
        $result = Fraction::fromString('-3/4');

        // Assert
        static::assertSame('-3/4', (string)$result);
    }

    public function testItParsesANegativeDenominator(): void
    {
        // Act
        $result = Fraction::fromString('3/-4');

        // Assert
        static::assertSame('-3/4', (string)$result);
    }

    public function testItParsesAnInteger(): void
    {
        // Act
        $result = Fraction::fromString('-2');

        // Assert
        static::assertSame('-2', (string)$result);
    }

    public function testItParsesAPositiveSign(): void
    {
        // Act
        $result = Fraction::fromString('+7');

        // Assert
        static::assertSame('7', (string)$result);
    }

    public function testItParsesADecimalExactly(): void
    {
        // Act
        $result = Fraction::fromString('0.125');

        // Assert
        static::assertSame('1/8', (string)$result);
    }

    public function testItParsesANegativeDecimal(): void
    {
        // Act
        $result = Fraction::fromString('-0.5');

        // Assert
        static::assertSame('-1/2', (string)$result);
    }

    public function testItParsesADecimalWithTrailingZeros(): void
    {
        // Act
        $result = Fraction::fromString('2.500');

        // Assert
        static::assertSame('5/2', (string)$result);
    }

    public function testItParsesAWholeDecimal(): void
    {
        // Act
        $result = Fraction::fromString('3.0');

        // Assert
        static::assertSame('3', (string)$result);
    }

    public function testItParsesALongDecimalExactly(): void
    {
        // Act
        $result = Fraction::fromString('0.1000000000000000055511151231257827');

        // Assert
        static::assertSame('1000000000000000055511151231257827/10000000000000000000000000000000000', (string)$result);
    }

    public function testItThrowsOnZeroDenominator(): void
    {
        // Assert
        $this->expectException(DivisionByZeroException::class);

        // Act
        Fraction::fromString('1/0');
    }

    #[DataProvider('provideItThrowsOnInvalidFormat')]
    public function testItThrowsOnInvalidFormat(string $value): void
    {
        // Assert
        $this->expectException(InvalidFormatException::class);

        // Act
        Fraction::fromString($value);
    }

    /** @return array<string, array<int, mixed>> */
    public static function provideItThrowsOnInvalidFormat(): array
    {
        return [
            'empty' => [''],
            'letters' => ['abc'],
            'whitespace' => [' 1/2'],
            'trailing newline' => ["1/2\n"],
            'exponent' => ['1e3'],
            'missing denominator' => ['1/'],
            'double slash' => ['1//2'],
            'fraction decimal' => ['1.5/2'],
            'no leading digit' => ['.5'],
            'no trailing digit' => ['5.'],
            'two dots' => ['1.2.3'],
            'hexadecimal' => ['0x1F'],
        ];
    }
}
