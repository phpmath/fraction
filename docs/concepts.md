---
title: Core concepts
intro: The few ideas you need to use Fraction well.
group: Getting started
order: 20
---

## Fractions are immutable

`Fraction` is a `final readonly` class. Once created, a fraction never changes. Methods such as `add()` or `negate()` return a new instance and leave the original untouched, so you can pass fractions around without defensive copies.

```php
use PHPMath\Fraction\Fraction;

$price = Fraction::of(3, 4);
$total = $price->add(Fraction::of(1, 4));

echo $price; // 3/4
echo $total; // 1
```

## Fractions are always normalised

Normalisation means storing a fraction in its simplest form. The library divides the numerator and the denominator by their greatest common divisor, and it moves any minus sign to the numerator. The denominator is therefore always positive.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(6, -8);  // -3/4
echo Fraction::of(10, 5);  // 2
echo Fraction::of(0, -5);  // 0
```

Because of this, two fractions with the same value always look the same, and `equals()` is a simple comparison. See [Comparison and sign](comparison.md).

## Creating fractions

`Fraction::of()` takes a numerator and an optional denominator, which defaults to 1. Each can be an `int`, a numeric string or a `BigInteger`. Use strings for numbers that do not fit in a PHP integer.

```php
use PHPMath\BigInteger\BigInteger;
use PHPMath\Fraction\Fraction;

echo Fraction::of(3, 4);                              // 3/4
echo Fraction::of(7);                                 // 7
echo Fraction::of('+6', '-8');                        // -3/4
echo Fraction::of(new BigInteger(6), new BigInteger(8)); // 3/4
```

`Fraction::fromString()` reads a whole text value. It accepts an integer (`'-2'`), a fraction (`'3/4'`) or a decimal (`'0.125'`). Decimals are converted exactly.

```php
use PHPMath\Fraction\Fraction;

echo Fraction::fromString('3/4');   // 3/4
echo Fraction::fromString('-2');    // -2
echo Fraction::fromString('0.125'); // 1/8
echo Fraction::fromString('-0.5');  // -1/2
```

Whitespace, exponents such as `'1e3'`, a missing digit before or after the point (`'.5'`, `'1.'`) and prefixes such as `'0x1F'` are rejected.

> [!NOTE]
> There is deliberately no `fromFloat()`. A float such as `0.1` is already inexact, so converting it would hide the problem. Pass a decimal string instead.

## Reading values

`numerator()` and `denominator()` return `BigInteger` objects. The numerator carries the sign.

```php
use PHPMath\Fraction\Fraction;

$fraction = Fraction::of(6, -8);

echo $fraction->numerator()->value();   // -3
echo $fraction->denominator()->value(); // 4
```

A `Fraction` can also be converted to text. `echo` and string casts give `numerator/denominator`, or only the numerator for a whole number. For decimals, integers and floats see [Rounding and conversion](rounding.md).

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(5, 2);  // 5/2
echo Fraction::of(-5, 2); // -5/2
echo Fraction::of(4, 2);  // 2
```

## Errors

A zero denominator, a zero divisor and text that is not a number all throw an exception that implements `FractionException`. Every exception is listed on the [Errors](errors.md) page.

```php
use PHPMath\Fraction\Exception\DivisionByZeroException;
use PHPMath\Fraction\Fraction;

try {
    Fraction::of(1, 0);
} catch (DivisionByZeroException $exception) {
    echo $exception->getMessage(); // The denominator cannot be zero.
}
```
