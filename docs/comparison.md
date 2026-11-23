---
title: Comparison and sign
intro: Compare fractions, test for equality and inspect the sign.
group: Guides
order: 40
---

## Equality

Use `equals()` to test whether two fractions have the same value. Fractions are normalised, so `1/2` and `2/4` are the same fraction. Do not use `==` or `===`. Compare through the methods of the library.

```php
use PHPMath\Fraction\Fraction;

$a = Fraction::of(1, 2);
$b = Fraction::of(2, 4);

var_dump($a->equals($b));                // bool(true)
var_dump($a->equals(Fraction::of(1, 3))); // bool(false)
```

## Ordering

`compareTo()` returns `-1` if the fraction is smaller, `0` if it is equal and `1` if it is greater. It compares the exact values, so it never suffers from rounding. This makes it a drop-in callback for `usort()`.

```php
use PHPMath\Fraction\Fraction;

$third = Fraction::of(1, 3);
$half = Fraction::of(1, 2);

echo $third->compareTo($half); // -1
echo $half->compareTo($third); // 1
echo $half->compareTo(Fraction::of(2, 4)); // 0

$list = [Fraction::of(3, 4), Fraction::of(1, 3), Fraction::of(-1, 2)];
usort($list, fn (Fraction $x, Fraction $y): int => $x->compareTo($y));

echo implode(', ', $list); // -1/2, 1/3, 3/4
```

## Zero and whole numbers

`isZero()` tells whether the fraction is exactly zero. `isInteger()` tells whether it is a whole number, which means its denominator is 1.

```php
use PHPMath\Fraction\Fraction;

var_dump(Fraction::of(0, 9)->isZero());   // bool(true)
var_dump(Fraction::of(6, 3)->isInteger()); // bool(true)
var_dump(Fraction::of(1, 3)->isInteger()); // bool(false)
```

## Sign

`sign()` gives the sign as a `Sign` enum from the BigInteger library. The sign belongs to the whole fraction, because the denominator is always positive.

```php
use PHPMath\Fraction\Fraction;

Fraction::of(-1, 3)->sign(); // Sign::Negative
Fraction::of(1, 3)->sign();  // Sign::Positive
Fraction::of(0)->sign();     // Sign::Zero
```

| Sign case | Meaning |
|---|---|
| `Sign::Positive` | The fraction is greater than zero. |
| `Sign::Negative` | The fraction is less than zero. |
| `Sign::Zero` | The fraction is exactly zero. |
