<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction\Tests\Fraction;

use PHPMath\BigInteger\BigInteger;
use PHPMath\Fraction\Fraction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function fdiv;
use function max;
use function mt_rand;
use function mt_srand;
use function str_repeat;
use function substr;

use const INF;
use const PHP_FLOAT_MAX;

#[CoversMethod(Fraction::class, 'toFloat')]
#[CoversMethod(Fraction::class, '__construct')]
#[CoversMethod(Fraction::class, 'of')]
final class ToFloatTest extends TestCase
{
    public function testItConvertsAnExactBinaryFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/8');

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(0.125, $result);
    }

    public function testItConvertsANegativeFraction(): void
    {
        // Arrange
        $fraction = Fraction::fromString('-3/4');

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(-0.75, $result);
    }

    public function testItConvertsOneThird(): void
    {
        // Arrange
        $fraction = Fraction::fromString('1/3');

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(1 / 3, $result);
    }

    public function testItConvertsZero(): void
    {
        // Arrange
        $fraction = Fraction::fromString('0');

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(0.0, $result);
    }

    public function testItConvertsHugeOperandsWithoutOverflow(): void
    {
        // Arrange
        $huge = (new BigInteger('10'))->pow(400);
        $numerator = $huge->multiply(new BigInteger(3));
        $denominator = $huge->multiply(new BigInteger(4))->add(new BigInteger(1));
        $fraction = Fraction::of($numerator, $denominator);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertEqualsWithDelta(0.75, $result, 1e-15);
    }

    public function testItConvertsAHugeNumeratorWithASmallDenominator(): void
    {
        // Arrange
        $fraction = Fraction::of((new BigInteger('10'))->pow(400), 2);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(INF, $result);
    }

    public function testItConvertsAHugeNegativeNumeratorToNegativeInfinity(): void
    {
        // Arrange
        $fraction = Fraction::of((new BigInteger('10'))->pow(400)->negate(), 2);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(-INF, $result);
    }

    public function testItConvertsAHugeDenominatorToZero(): void
    {
        // Arrange
        $fraction = Fraction::of(1, (new BigInteger('10'))->pow(400));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(0.0, $result);
    }

    public function testItConvertsAHugeDenominatorWithAHugeNumeratorOfSimilarSize(): void
    {
        // Arrange
        $numerator = (new BigInteger('10'))->pow(399)->multiply(new BigInteger(5));
        $denominator = (new BigInteger('10'))->pow(400);
        $fraction = Fraction::of($numerator, $denominator);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertEqualsWithDelta(0.5, $result, 1e-15);
    }

    public function testItRoundsOneTenthCorrectly(): void
    {
        // Arrange
        $fraction = Fraction::of(1, 10);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(0.1, $result);
    }

    public function testItRoundsOneFifthCorrectly(): void
    {
        // Arrange
        $fraction = Fraction::of(1, 5);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(0.2, $result);
    }

    public function testItRoundsTwoThirdsCorrectly(): void
    {
        // Arrange
        $fraction = Fraction::of(2, 3);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(2 / 3, $result);
    }

    public function testItKeepsAnExactLargeIntegerExact(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(1000));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::scale(1.0, 1000), $result);
    }

    public function testItReturnsPositiveZeroForZeroWithAnyDenominator(): void
    {
        // Arrange
        $fraction = Fraction::of(0, -7);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame('0', (string)$result);
    }

    public function testItRoundsATieToEvenDownwards(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(53)->add(new BigInteger(1)), self::pow2(53));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(1.0, $result);
    }

    public function testItRoundsATieToEvenUpwards(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(53)->add(new BigInteger(3)), self::pow2(53));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(1.0 + self::scale(1.0, -51), $result);
    }

    public function testItRoundsJustAboveATieUpwards(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(54)->add(new BigInteger(3)), self::pow2(54));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(1.0 + self::scale(1.0, -52), $result);
    }

    public function testItReturnsTheLargestFloatForTheLargestFloat(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(53)->subtract(new BigInteger(1))->multiply(self::pow2(971)));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(PHP_FLOAT_MAX, $result);
    }

    public function testItRoundsJustBelowTheOverflowThresholdToTheLargestFloat(): void
    {
        // Arrange
        $threshold = self::pow2(54)->subtract(new BigInteger(1))->multiply(self::pow2(970));
        $fraction = Fraction::of($threshold->subtract(new BigInteger(1)));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(PHP_FLOAT_MAX, $result);
    }

    public function testItOverflowsAtTheTieAboveTheLargestFloat(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(54)->subtract(new BigInteger(1))->multiply(self::pow2(970)));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(INF, $result);
    }

    public function testItOverflowsToNegativeInfinityForANegativeValue(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(1024)->negate());

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(-INF, $result);
    }

    public function testItConvertsTheLargestNegativeFloat(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(53)->subtract(new BigInteger(1))->multiply(self::pow2(971))->negate());

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(-PHP_FLOAT_MAX, $result);
    }

    public function testItConvertsTheSmallestSubnormal(): void
    {
        // Arrange
        $fraction = Fraction::of(1, self::pow2(1074));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::scale(1.0, -1074), $result);
    }

    public function testItConvertsASubnormalTieToEven(): void
    {
        // Arrange
        $fraction = Fraction::of(3, self::pow2(1075));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::scale(2.0, -1074), $result);
    }

    public function testItRoundsHalfOfTheSmallestSubnormalToZero(): void
    {
        // Arrange
        $fraction = Fraction::of(1, self::pow2(1075));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame('0', (string)$result);
    }

    public function testItRoundsJustAboveHalfOfTheSmallestSubnormalUp(): void
    {
        // Arrange
        $fraction = Fraction::of(self::pow2(10)->add(new BigInteger(1)), self::pow2(1085));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::scale(1.0, -1074), $result);
    }

    public function testItDoesNotProduceNegativeZeroOnUnderflow(): void
    {
        // Arrange
        $fraction = Fraction::of(-1, self::pow2(1100));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(INF, fdiv(1.0, $result));
    }

    public function testItConvertsNegativeSubnormalValues(): void
    {
        // Arrange
        $fraction = Fraction::of(-3, self::pow2(1074));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::scale(-3.0, -1074), $result);
    }

    public function testItConvertsTheSmallestNormalFloat(): void
    {
        // Arrange
        $fraction = Fraction::of(1, self::pow2(1022));

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::scale(1.0, -1022), $result);
    }

    public function testItRoundsHugeOperandsCorrectly(): void
    {
        // Arrange
        $numerator = new BigInteger(str_repeat('7', 400));
        $denominator = new BigInteger('9' . str_repeat('3', 399));
        $fraction = Fraction::of($numerator, $denominator);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::reference($fraction), $result);
    }

    public function testItRoundsHugeOperandsWithAHugeResultCorrectly(): void
    {
        // Arrange
        $numerator = new BigInteger('3' . str_repeat('1', 399));
        $denominator = new BigInteger('7' . str_repeat('9', 99));
        $fraction = Fraction::of($numerator, $denominator);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::reference($fraction), $result);
    }

    public function testItRoundsHugeOperandsWithATinyResultCorrectly(): void
    {
        // Arrange
        $numerator = new BigInteger('3' . str_repeat('1', 99));
        $denominator = new BigInteger('7' . str_repeat('9', 399));
        $fraction = Fraction::of($numerator, $denominator);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::reference($fraction), $result);
    }

    public function testItRoundsANegativeFractionCorrectly(): void
    {
        // Arrange
        $fraction = Fraction::of(-123456789, 987654321);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::reference($fraction), $result);
    }

    /**
     * @param numeric-string $numerator
     * @param numeric-string $denominator
     */
    #[DataProvider('provideRandomFractions')]
    public function testItMatchesTheExactReferenceForRandomFractions(string $numerator, string $denominator): void
    {
        // Arrange
        $fraction = Fraction::of($numerator, $denominator);

        // Act
        $result = $fraction->toFloat();

        // Assert
        static::assertSame(self::reference($fraction), $result);
    }

    /** @return iterable<string, array{string, string}> */
    public static function provideRandomFractions(): iterable
    {
        mt_srand(20260101);

        for ($index = 0; $index < 400; ++$index) {
            $sign = mt_rand(0, 1) === 1 ? '-' : '';

            if ($index % 4 === 3) {
                // Denominators that are a power of two times a small number reach into the subnormal range.
                $numerator = self::randomDigits(mt_rand(1, 30));
                $denominator = self::pow2(mt_rand(900, 1100))->multiply(new BigInteger(mt_rand(1, 999)))->value();
            } else {
                $numerator = self::randomDigits(mt_rand(1, 60));
                $denominator = self::randomDigits(mt_rand(1, 60));
            }

            yield 'random ' . $index => [$sign . $numerator, $denominator];
        }
    }

    /**
     * Multiplies a float by two to the power of the exponent, which is exact when the result is representable.
     */
    private static function scale(float $value, int $exponent): float
    {
        return $value * 2.0 ** $exponent;
    }

    private static function randomDigits(int $length): string
    {
        $digits = (string)mt_rand(1, 9);

        for ($index = 1; $index < $length; ++$index) {
            $digits .= (string)mt_rand(0, 9);
        }

        return $digits;
    }

    private static function pow2(int $exponent): BigInteger
    {
        return (new BigInteger(2))->pow($exponent);
    }

    /**
     * Compares numerator / denominator to 2^exponent.
     */
    private static function compareToPowerOfTwo(BigInteger $numerator, BigInteger $denominator, int $exponent): int
    {
        if ($exponent >= 0) {
            return $numerator->cmp($denominator->multiply(self::pow2($exponent)));
        }

        return $numerator->multiply(self::pow2(-$exponent))->cmp($denominator);
    }

    /**
     * The nearest float computed with exact big integer arithmetic, ties to even, without the algorithm under test.
     */
    private static function reference(Fraction $fraction): float
    {
        $numerator = $fraction->numerator()->abs();
        $denominator = $fraction->denominator();

        if ($numerator->isZero()) {
            return 0.0;
        }

        // Find e such that 2^e <= |value| < 2^(e + 1).
        $exponent = 0;

        while (self::compareToPowerOfTwo($numerator, $denominator, $exponent + 1) >= 0) {
            ++$exponent;
        }

        while (self::compareToPowerOfTwo($numerator, $denominator, $exponent) < 0) {
            --$exponent;
        }

        // The weight of the last kept bit.
        $lsb = max($exponent - 52, -1074);

        // The value in units of 2^lsb is (numerator * 2^-lsb) / denominator.
        $scaledNumerator = $lsb < 0 ? $numerator->multiply(self::pow2(-$lsb)) : $numerator;
        $scaledDenominator = $lsb > 0 ? $denominator->multiply(self::pow2($lsb)) : $denominator;
        $units = $scaledNumerator->divide($scaledDenominator);
        $twiceRemainder = $scaledNumerator->divideRemainder($scaledDenominator)->multiply(new BigInteger(2));
        $comparison = $twiceRemainder->cmp($scaledDenominator);
        $odd = ((int)substr($units->value(), -1)) % 2 === 1;

        if ($comparison > 0 || ($comparison === 0 && $odd)) {
            $units = $units->add(new BigInteger(1));
        }

        $magnitude = $units->isZero() ? 0.0 : self::scale((float)$units->value(), $lsb);

        return $fraction->numerator()->isNegative() && $magnitude !== 0.0 ? -$magnitude : $magnitude;
    }
}
