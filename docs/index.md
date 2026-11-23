---
title: Introduction
intro: Exact rational numbers for PHP, built on phpmath/biginteger. Every fraction is an immutable value object.
group: Getting started
order: 10
---

## Requirements

The library needs a current PHP version, the GMP extension and the BigInteger library, which holds the numbers.

- PHP 8.4 or newer
- The `gmp` extension
- `phpmath/biginteger` 3.0 or newer (installed automatically)

## Installation

Install the library with Composer. This also installs BigInteger and checks that the GMP extension is available.

```bash
composer require phpmath/fraction
```

## A first example

A fraction has a numerator (the number above the line) and a denominator (the number below it). Create one with `Fraction::of()` or from a string such as `'0.125'`. The result is always shown in its simplest form.

```php
use PHPMath\Fraction\Fraction;

$a = Fraction::of(1, 3);
$b = Fraction::fromString('0.125'); // 1/8

echo $a->add($b);      // 11/24
echo $a->multiply($b); // 1/24
```

Calling `add()` did not change `$a`. It returned a new `Fraction`. This is true for every operation in the library.

Because fractions are exact, there is no rounding error. Compare this with floats, where `0.1 + 0.2 == 0.3` is `false`.

```php
use PHPMath\Fraction\Fraction;

$sum = Fraction::fromString('0.1')->add(Fraction::fromString('0.2'));

echo $sum; // 3/10
var_dump($sum->equals(Fraction::fromString('0.3'))); // bool(true)
```

## Where to go next

Start with the core concepts, or jump straight to the topic you need.

<div class="cards"><a href="concepts.md"><strong>Core concepts</strong><span>Immutability, creating fractions and reading values.</span></a><a href="arithmetic.md"><strong>Arithmetic</strong><span>Add, divide, powers and reciprocals.</span></a><a href="comparison.md"><strong>Comparison and sign</strong><span>Equality, ordering and testing for zero.</span></a><a href="rounding.md"><strong>Rounding and conversion</strong><span>Floor, ceil, round, decimal strings and floats.</span></a><a href="api.md"><strong>API reference</strong><span>Every method with its parameters and errors.</span></a></div>
