<?php

/**
 * phpmath / fraction (https://github.com/phpmath/fraction)
 *
 * @link https://github.com/phpmath/fraction for the canonical source repository
 */

declare(strict_types=1);

namespace PHPMath\Fraction;

use PHPMath\BigInteger\BigInteger;
use PHPMath\BigInteger\Sign;
use PHPMath\Fraction\Exception\DivisionByZeroException;
use PHPMath\Fraction\Exception\InvalidFormatException;
use PHPMath\Fraction\Exception\InvalidScaleException;
use Stringable;

use function ceil;
use function is_int;
use function ltrim;
use function max;
use function preg_match;
use function str_pad;
use function strlen;
use function substr;

use const STR_PAD_LEFT;

/**
 * An immutable, exact rational number.
 *
 * A fraction is always normalized: it is stored in lowest terms and its denominator is always positive.
 * All operations return a new instance, leaving the original unchanged.
 */
final readonly class Fraction implements Stringable
{
    /**
     * The minimum number of significant bits of the integer quotient that toFloat() computes.
     */
    private const int FLOAT_QUOTIENT_BITS = 57;

    /**
     * The exponent of the least significant bit of the smallest subnormal float.
     */
    private const int FLOAT_MIN_EXPONENT = -1074;

    /**
     * A lower bound of log2(10), used to estimate bit lengths from digit counts.
     */
    private const float LOG2_OF_TEN_LOWER = 3.3219;

    /**
     * An upper bound of log2(10), used to estimate bit lengths from digit counts.
     */
    private const float LOG2_OF_TEN_UPPER = 3.3220;

    /**
     * The numerator (carries the sign).
     */
    private BigInteger $numerator;

    /**
     * The denominator (always positive).
     */
    private BigInteger $denominator;

    /**
     * Creates a fraction from an already normalized numerator and denominator.
     *
     * @param BigInteger $numerator   The numerator.
     * @param BigInteger $denominator The denominator, which must be positive.
     */
    private function __construct(BigInteger $numerator, BigInteger $denominator)
    {
        $this->numerator = $numerator;
        $this->denominator = $denominator;
    }

    /**
     * Creates a normalized fraction from a numerator and a denominator.
     *
     * Integer strings may have a leading sign. Use fromString() for fractions and decimals.
     *
     * @param BigInteger|int|string $numerator   The numerator.
     * @param BigInteger|int|string $denominator The denominator.
     *
     * @throws DivisionByZeroException If the denominator is zero.
     * @throws InvalidFormatException If a string is not a valid integer.
     */
    public static function of(BigInteger|int|string $numerator, BigInteger|int|string $denominator = 1): self
    {
        $numerator = self::toBigInteger($numerator);
        $denominator = self::toBigInteger($denominator);

        if ($denominator->isZero()) {
            throw DivisionByZeroException::denominator();
        }

        if ($denominator->isNegative()) {
            $numerator = $numerator->negate();
            $denominator = $denominator->negate();
        }

        $gcd = $numerator->greatestCommonDivisor($denominator);

        if (!$gcd->equals(new BigInteger(1))) {
            $numerator = $numerator->divide($gcd);
            $denominator = $denominator->divide($gcd);
        }

        return new self($numerator, $denominator);
    }

    /**
     * Creates a fraction from a string such as "3/4", "-2" or "0.125".
     *
     * Decimal strings are converted exactly. Whitespace, exponents and radix prefixes are not accepted.
     *
     * @param string $value The string to parse.
     *
     * @throws InvalidFormatException If the string is not a valid number.
     * @throws DivisionByZeroException If the denominator is zero.
     */
    public static function fromString(string $value): self
    {
        if (preg_match('~^([+-]?\d+)/([+-]?\d+)$~D', $value, $matches) === 1) {
            return self::of($matches[1], $matches[2]);
        }

        if (preg_match('~^([+-]?)(\d+)\.(\d+)$~D', $value, $matches) === 1) {
            $digits = $matches[3];

            return self::of(
                $matches[1] . $matches[2] . $digits,
                (new BigInteger(10))->pow(strlen($digits)),
            );
        }

        if (preg_match('~^[+-]?\d+$~D', $value) === 1) {
            return self::of($value);
        }

        throw InvalidFormatException::fromValue($value);
    }

    /**
     * Returns the numerator.
     */
    public function numerator(): BigInteger
    {
        return $this->numerator;
    }

    /**
     * Returns the denominator, which is always positive.
     */
    public function denominator(): BigInteger
    {
        return $this->denominator;
    }

    /**
     * Adds a fraction to this fraction.
     *
     * @param Fraction $value The fraction to add.
     */
    public function add(Fraction $value): self
    {
        return self::of(
            $this->numerator->multiply($value->denominator)->add($value->numerator->multiply($this->denominator)),
            $this->denominator->multiply($value->denominator),
        );
    }

    /**
     * Subtracts a fraction from this fraction.
     *
     * @param Fraction $value The fraction to subtract.
     */
    public function subtract(Fraction $value): self
    {
        return $this->add($value->negate());
    }

    /**
     * Multiplies this fraction by another fraction.
     *
     * @param Fraction $value The fraction to multiply by.
     */
    public function multiply(Fraction $value): self
    {
        return self::of(
            $this->numerator->multiply($value->numerator),
            $this->denominator->multiply($value->denominator),
        );
    }

    /**
     * Divides this fraction by another fraction.
     *
     * @param Fraction $value The divisor.
     *
     * @throws DivisionByZeroException If the divisor is zero.
     */
    public function divide(Fraction $value): self
    {
        if ($value->isZero()) {
            throw DivisionByZeroException::divisor();
        }

        return $this->multiply($value->reciprocal());
    }

    /**
     * Returns the negated fraction.
     */
    public function negate(): self
    {
        return new self($this->numerator->negate(), $this->denominator);
    }

    /**
     * Returns the absolute value.
     */
    public function abs(): self
    {
        return new self($this->numerator->abs(), $this->denominator);
    }

    /**
     * Returns the reciprocal (1 / this).
     *
     * @throws DivisionByZeroException If this fraction is zero.
     */
    public function reciprocal(): self
    {
        if ($this->isZero()) {
            throw DivisionByZeroException::divisor();
        }

        if ($this->numerator->isNegative()) {
            return new self($this->denominator->negate(), $this->numerator->negate());
        }

        return new self($this->denominator, $this->numerator);
    }

    /**
     * Raises this fraction to an integer power. Any fraction to the power of zero is one.
     *
     * @param int $exponent The exponent, which may be negative.
     *
     * @throws DivisionByZeroException If this fraction is zero and the exponent is negative.
     */
    public function pow(int $exponent): self
    {
        if ($exponent < 0) {
            if ($this->isZero()) {
                throw DivisionByZeroException::zeroToNegativePower();
            }

            return $this->reciprocal()->pow(-$exponent);
        }

        return new self($this->numerator->pow($exponent), $this->denominator->pow($exponent));
    }

    /**
     * Compares this fraction to another one.
     *
     * @param Fraction $value The fraction to compare to.
     *
     * @return int -1 if this is smaller, 0 if equal, 1 if greater.
     */
    public function compareTo(Fraction $value): int
    {
        $left = $this->numerator->multiply($value->denominator);
        $right = $value->numerator->multiply($this->denominator);

        return $left->cmp($right) <=> 0;
    }

    /**
     * Checks whether this fraction equals another one.
     *
     * @param Fraction $value The fraction to compare to.
     */
    public function equals(Fraction $value): bool
    {
        return $this->numerator->equals($value->numerator) && $this->denominator->equals($value->denominator);
    }

    /**
     * Checks whether this fraction is zero.
     */
    public function isZero(): bool
    {
        return $this->numerator->isZero();
    }

    /**
     * Checks whether this fraction is a whole number.
     */
    public function isInteger(): bool
    {
        return $this->denominator->equals(new BigInteger(1));
    }

    /**
     * Returns the sign of this fraction.
     */
    public function sign(): Sign
    {
        return $this->numerator->sign();
    }

    /**
     * Returns the largest integer that is less than or equal to this fraction.
     */
    public function floor(): BigInteger
    {
        $quotient = $this->numerator->divide($this->denominator);

        if ($this->numerator->isNegative() && !$this->isInteger()) {
            return $quotient->subtract(new BigInteger(1));
        }

        return $quotient;
    }

    /**
     * Returns the smallest integer that is greater than or equal to this fraction.
     */
    public function ceil(): BigInteger
    {
        return $this->negate()->floor()->negate();
    }

    /**
     * Returns the integer part of this fraction, rounding towards zero.
     */
    public function truncate(): BigInteger
    {
        return $this->numerator->divide($this->denominator);
    }

    /**
     * Rounds this fraction to the nearest integer, with halves rounded away from zero.
     */
    public function round(): BigInteger
    {
        $two = new BigInteger(2);
        $rounded = $this->numerator->abs()->multiply($two)->add($this->denominator)
            ->divide($this->denominator->multiply($two));

        return $this->numerator->isNegative() ? $rounded->negate() : $rounded;
    }

    /**
     * Returns the float that is nearest to this fraction. Floats are inexact, so this is a lossy conversion.
     *
     * The result is correctly rounded for any size of numerator and denominator: it is the float closest to the exact
     * value, and an exact tie is resolved to the float with an even last bit (the IEEE 754 default). A value above
     * PHP_FLOAT_MAX that is too far from it to round down gives INF or -INF. Subnormal results are rounded to the
     * available precision, a value below half of the smallest subnormal gives 0.0, and negative zero is never returned.
     */
    public function toFloat(): float
    {
        if ($this->numerator->isZero()) {
            return 0.0;
        }

        $numerator = $this->numerator->abs();
        $denominator = $this->denominator;

        // Scale so that the integer quotient has at least 55 significant bits (53 plus a guard and a rounding bit).
        $shift = (int)ceil(
            self::FLOAT_QUOTIENT_BITS
            + (strlen($denominator->value()) * self::LOG2_OF_TEN_UPPER + 1)
            - ((strlen($numerator->value()) - 1) * self::LOG2_OF_TEN_LOWER),
        );
        $two = new BigInteger(2);

        if ($shift >= 0) {
            $numerator = $numerator->multiply($two->pow($shift));
        } else {
            $denominator = $denominator->multiply($two->pow(-$shift));
        }

        $quotient = $numerator->divide($denominator);
        $sticky = !$numerator->divideRemainder($denominator)->isZero();

        // The value is $quotient * 2^-$shift, with $bits significant bits in the quotient.
        $bits = 0;
        $power = new BigInteger(1);

        while ($power->cmp($quotient) <= 0) {
            $power = $power->multiply($two);
            ++$bits;
        }

        // The exponent of the last kept bit: 53 bits for normal results, fewer for subnormal results.
        $exponent = max($bits - 1 - $shift - 52, self::FLOAT_MIN_EXPONENT);
        $divisor = $two->pow($exponent + $shift);
        $mantissa = $quotient->divide($divisor);
        $comparison = $quotient->divideRemainder($divisor)->multiply($two)->cmp($divisor);

        if ($comparison > 0 || ($comparison === 0 && ($sticky || !$mantissa->divideRemainder($two)->isZero()))) {
            $mantissa = $mantissa->add(new BigInteger(1));
        }

        if ($mantissa->isZero()) {
            return 0.0;
        }

        // Both factors are exact (the mantissa has at most 54 bits and the power of two is exact), so is the product.
        $result = (float)$mantissa->value() * 2.0 ** $exponent;

        return $this->numerator->isNegative() ? -$result : $result;
    }

    /**
     * Returns a decimal string with exactly the given number of decimals, rounded half away from zero.
     *
     * @param int $scale The number of decimals.
     *
     * @throws InvalidScaleException If the scale is negative.
     */
    public function toDecimalString(int $scale): string
    {
        if ($scale < 0) {
            throw InvalidScaleException::fromScale($scale);
        }

        $scaled = $this->multiply(new self((new BigInteger(10))->pow($scale), new BigInteger(1)))->round();
        $digits = $scaled->abs()->value();

        if ($scale > 0) {
            $digits = str_pad($digits, $scale + 1, '0', STR_PAD_LEFT);
            $digits = substr($digits, 0, -$scale) . '.' . substr($digits, -$scale);
        }

        return ($scaled->isNegative() ? '-' : '') . $digits;
    }

    /**
     * Returns "numerator/denominator", or just the numerator for whole numbers.
     */
    public function __toString(): string
    {
        if ($this->isInteger()) {
            return $this->numerator->value();
        }

        return $this->numerator->value() . '/' . $this->denominator->value();
    }

    /**
     * Converts a supported input to a BigInteger.
     *
     * @param BigInteger|int|string $value The value to convert.
     *
     * @throws InvalidFormatException If a string is not a valid integer.
     */
    private static function toBigInteger(BigInteger|int|string $value): BigInteger
    {
        if ($value instanceof BigInteger) {
            return $value;
        }

        if (is_int($value)) {
            return new BigInteger($value);
        }

        if (preg_match('~^([+-]?)(\d+)$~D', $value, $matches) !== 1) {
            throw InvalidFormatException::fromValue($value);
        }

        $digits = ltrim($matches[2], '0');

        return new BigInteger(($matches[1] === '-' ? '-' : '') . ($digits === '' ? '0' : $digits));
    }
}
