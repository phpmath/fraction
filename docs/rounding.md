---
title: Rounding and conversion
intro: Turn a fraction into a whole number, a decimal string or a float.
group: Guides
order: 50
---

## Rounding to a whole number

Rounding turns a fraction into the whole number that is closest in some direction. Four methods exist, and each returns a `BigInteger`. They differ for fractions that are not whole numbers, and most of all for negative ones.

| Method | Rule | 7/2 | -7/2 | 2/3 | -2/3 |
|---|---|---|---|---|---|
| `floor()` | Largest integer that is not greater than the fraction. | `3` | `-4` | `0` | `-1` |
| `ceil()` | Smallest integer that is not less than the fraction. | `4` | `-3` | `1` | `0` |
| `truncate()` | Drops the fractional part, rounds toward zero. | `3` | `-3` | `0` | `0` |
| `round()` | Nearest integer, halves round away from zero. | `4` | `-4` | `1` | `-1` |

```php
use PHPMath\Fraction\Fraction;

$fraction = Fraction::of(-7, 2);

echo $fraction->floor()->value();    // -4
echo $fraction->ceil()->value();     // -3
echo $fraction->truncate()->value(); // -3
echo $fraction->round()->value();    // -4
```

A result of zero never has a minus sign, because a `BigInteger` has no negative zero.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(-1, 3)->round()->value();    // 0
echo Fraction::of(-1, 2)->ceil()->value();     // 0
echo Fraction::of(-1, 2)->truncate()->value(); // 0
```

A whole number is returned unchanged by all four methods.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(-4)->floor()->value(); // -4
echo Fraction::of(-4)->round()->value(); // -4
```

## Halves

A half is a fraction that lies exactly between two whole numbers, such as `5/2`. `round()` always rounds a half away from zero, so `5/2` becomes `3` and `-5/2` becomes `-3`.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(5, 2)->round()->value();  // 3
echo Fraction::of(-5, 2)->round()->value(); // -3
echo Fraction::of(1, 3)->round()->value();  // 0
```

## Decimal strings

`toDecimalString()` writes the fraction as a decimal number with exactly the number of decimals you ask for. The scale is that number of decimals. The last digit is rounded with the same rule as `round()`, half away from zero. The result is a string, so it keeps trailing zeros and has no float error.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(1, 3)->toDecimalString(5);    // 0.33333
echo Fraction::of(1, 8)->toDecimalString(2);    // 0.13
echo Fraction::of(7)->toDecimalString(3);       // 7.000
echo Fraction::of(-1234, 100)->toDecimalString(1); // -12.3
echo Fraction::of(5, 2)->toDecimalString(0);    // 3
```

The scale is not limited by the float precision, so you can ask for as many digits as you need.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(1, 3)->toDecimalString(30); // 0.333333333333333333333333333333
```

If the rounded value is zero, the result has no minus sign.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(-1, 1000)->toDecimalString(2); // 0.00
```

> [!WARNING]
> A negative scale throws [`InvalidScaleException`](errors.md). The scale must be zero or greater.

## Floats

`toFloat()` returns a PHP `float`. This is a lossy conversion, because most fractions have no exact float. Use it only for display or for calling code that needs a float, and keep your calculations in `Fraction`.

```php
use PHPMath\Fraction\Fraction;

var_dump(Fraction::of(1, 4)->toFloat());   // float(0.25)
var_dump(Fraction::of(1, 3)->toFloat());   // float(0.3333333333333333)
var_dump(Fraction::of(-22, 7)->toFloat()); // float(-3.142857142857143)
var_dump(Fraction::of(1, 10)->toFloat());  // float(0.1)
```

## Float accuracy

The result of `toFloat()` is correctly rounded: it is the float that is nearest to the exact value of the fraction, whatever the size of the numerator and denominator. When the exact value lies precisely halfway between two floats, the result is the one with an even last bit (ties to even, the IEEE 754 default). The conversion uses exact integer arithmetic, so there is no double rounding.

A value that is too large for a float gives `INF` or `-INF`. The threshold is the usual one: a value rounds to `INF` when it is at or above the halfway point between `PHP_FLOAT_MAX` and the next power of two. Very small values lose precision gradually (subnormal floats) and finally become `0.0` below half of the smallest subnormal. Negative zero is never returned.

```php
use PHPMath\BigInteger\BigInteger;
use PHPMath\Fraction\Fraction;

$huge = (new BigInteger(10))->pow(400);

var_dump(Fraction::of($huge, 2)->toFloat());           // float(INF)
var_dump(Fraction::of($huge->negate(), 2)->toFloat()); // float(-INF)
var_dump(Fraction::of(1, $huge)->toFloat());           // float(0)
var_dump(Fraction::of((new BigInteger(10))->pow(399)->multiply(new BigInteger(5)), $huge)->toFloat()); // float(0.5)
```

The last line shows that two huge parts of similar size still give a sensible result. The smallest subnormal float is `2^-1074`, and exact halves round to the even neighbour:

```php
use PHPMath\BigInteger\BigInteger;
use PHPMath\Fraction\Fraction;

$two = new BigInteger(2);

var_dump(Fraction::of(1, $two->pow(1074))->toFloat()); // float(5.0E-324)
var_dump(Fraction::of(1, $two->pow(1075))->toFloat()); // float(0), a tie that rounds to the even zero
var_dump(Fraction::of(3, $two->pow(1075))->toFloat()); // float(1.0E-323), a tie that rounds to the even neighbour
var_dump(Fraction::of($two->pow(53)->add(new BigInteger(1)), $two->pow(53))->toFloat()); // float(1)
```

## There is no fromFloat

The library has no way to create a fraction from a float, on purpose. `0.1` as a float is not exactly one tenth, so the result would not be what you expect. Give the value as a decimal string instead.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::fromString('0.1'); // 1/10
```
