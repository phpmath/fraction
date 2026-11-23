---
title: Arithmetic
intro: Add, subtract, multiply and divide, then reciprocals and powers.
group: Guides
order: 30
---

## The four operations

`add()`, `subtract()`, `multiply()` and `divide()` take another `Fraction` and return the result as a new one. The results are exact and always normalised, because there is no overflow and no rounding.

```php
use PHPMath\Fraction\Fraction;

$a = Fraction::of(1, 3);
$b = Fraction::fromString('0.125'); // 1/8

echo $a->add($b);      // 11/24
echo $a->subtract($b); // 5/24
echo $a->multiply($b); // 1/24
echo $a->divide($b);   // 8/3
```

Methods that combine two fractions accept a `Fraction`, not an int or a string. Wrap raw values with `Fraction::of()` first.

```php
use PHPMath\Fraction\Fraction;

$sum = Fraction::of(1, 2)->add(Fraction::of(1, 3))->add(Fraction::of(1, 6));

echo $sum; // 1
```

> [!WARNING]
> Dividing by zero is an error. `divide()` throws [`DivisionByZeroException`](errors.md) when the divisor is zero.

## Summing a list

The same exactness holds for long sums. Use `array_reduce()` and start from `Fraction::of(0)`.

```php
use PHPMath\Fraction\Fraction;

$harmonic = array_reduce(
    range(1, 10),
    fn (Fraction $carry, int $n): Fraction => $carry->add(Fraction::of(1, $n)),
    Fraction::of(0),
);

echo $harmonic; // 7381/2520
```

## Sign and magnitude

`abs()` returns the fraction without its sign. `negate()` flips the sign, so a positive fraction becomes negative and the other way around. Zero stays zero.

```php
use PHPMath\Fraction\Fraction;

$fraction = Fraction::of(-3, 4);

echo $fraction->abs();    // 3/4
echo $fraction->negate(); // 3/4
echo Fraction::of(0)->negate(); // 0
```

## Reciprocal

The reciprocal of a fraction is 1 divided by that fraction. The numerator and the denominator swap places, and the sign stays on the numerator. The reciprocal of zero does not exist, so `reciprocal()` throws [`DivisionByZeroException`](errors.md).

```php
use PHPMath\Fraction\Fraction;

echo Fraction::of(3, 4)->reciprocal();  // 4/3
echo Fraction::of(-3, 4)->reciprocal(); // -4/3
echo Fraction::of(5)->reciprocal();     // 1/5
```

## Powers

`pow()` raises a fraction to an integer power. The exponent is a PHP `int` and may be negative. A negative exponent takes the reciprocal first. Any fraction to the power of zero is 1.

```php
use PHPMath\Fraction\Fraction;

$base = Fraction::of(2, 3);

echo $base->pow(3);                 // 8/27
echo $base->pow(-2);                // 9/4
echo $base->pow(0);                 // 1
echo Fraction::of(-2, 3)->pow(-3);  // -27/8
```

Zero to a negative power is an error, because it means dividing by zero. It throws [`DivisionByZeroException`](errors.md). Zero to the power of zero is 1.

```php
use PHPMath\Fraction\Exception\DivisionByZeroException;
use PHPMath\Fraction\Fraction;

echo Fraction::of(0)->pow(0); // 1

try {
    Fraction::of(0)->pow(-1);
} catch (DivisionByZeroException $exception) {
    echo $exception->getMessage(); // Cannot raise zero to a negative power.
}
```
