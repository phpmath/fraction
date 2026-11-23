---
title: API reference
intro: Every public method of Fraction, grouped by purpose.
group: Reference
order: 60
layout: reference
---

Every method returns a new `Fraction` unless the return type says otherwise. All methods belong to `PHPMath\Fraction\Fraction`, a `final readonly` class that implements `Stringable`. A numerator is the number above the line of a fraction and a denominator is the number below it. Both are `BigInteger` objects from `PHPMath\BigInteger\BigInteger`.

## Construction

Create a fraction from a numerator and a denominator, or from text. The constructor is private, so use one of these named constructors. The result is always normalised, which means it is in lowest terms and its denominator is positive.

### of()

```php
public static function of(BigInteger|int|string $numerator, BigInteger|int|string $denominator = 1): self
```

Creates a normalised fraction. Strings must be integers and may have a leading sign. Use `fromString()` for fractions and decimals.

- **Parameters:** `$numerator` the numerator; `$denominator` the denominator, 1 by default
- **Returns:** `Fraction`
- **Throws:** [DivisionByZeroException](errors.md) if the denominator is zero; [InvalidFormatException](errors.md) if a string is not a valid integer

### fromString()

```php
public static function fromString(string $value): self
```

Creates a fraction from text such as `'3/4'`, `'-2'` or `'0.125'`. Decimals are converted exactly. Whitespace, exponents and radix prefixes are not accepted.

- **Parameters:** `$value` an integer, a fraction or a decimal, each with an optional sign
- **Returns:** `Fraction`
- **Throws:** [InvalidFormatException](errors.md) if the text is not a valid number; [DivisionByZeroException](errors.md) if the denominator is zero

## Reading values

Read the numerator and the denominator, or convert the fraction to text.

### numerator()

```php
public function numerator(): BigInteger
```

The numerator. It carries the sign of the fraction.

- **Returns:** `BigInteger`

### denominator()

```php
public function denominator(): BigInteger
```

The denominator. It is always positive.

- **Returns:** `BigInteger`

### __toString()

```php
public function __toString(): string
```

Returns `numerator/denominator`, or only the numerator for a whole number.

- **Returns:** `string`

## Arithmetic

Add, subtract, multiply, divide, and take reciprocals and powers. All results are exact and normalised.

### add()

```php
public function add(Fraction $value): self
```

Adds a fraction.

- **Parameters:** `$value` the other operand
- **Returns:** `Fraction`

### subtract()

```php
public function subtract(Fraction $value): self
```

Subtracts a fraction.

- **Parameters:** `$value` the other operand
- **Returns:** `Fraction`

### multiply()

```php
public function multiply(Fraction $value): self
```

Multiplies by a fraction.

- **Parameters:** `$value` the other operand
- **Returns:** `Fraction`

### divide()

```php
public function divide(Fraction $value): self
```

Divides by a fraction.

- **Parameters:** `$value` the divisor
- **Returns:** `Fraction`
- **Throws:** [DivisionByZeroException](errors.md) if the divisor is zero

### negate()

```php
public function negate(): self
```

Flips the sign. Zero stays zero.

- **Returns:** `Fraction`

### abs()

```php
public function abs(): self
```

The absolute value.

- **Returns:** `Fraction`

### reciprocal()

```php
public function reciprocal(): self
```

Returns 1 divided by this fraction. The sign stays on the numerator.

- **Returns:** `Fraction`
- **Throws:** [DivisionByZeroException](errors.md) if the fraction is zero

### pow()

```php
public function pow(int $exponent): self
```

Raises the fraction to an integer power. A negative exponent takes the reciprocal first. Any fraction to the power of zero is 1, including zero.

- **Parameters:** `$exponent` any integer, which may be negative
- **Returns:** `Fraction`
- **Throws:** [DivisionByZeroException](errors.md) if the fraction is zero and the exponent is negative

## Comparison and sign

Compare fractions and inspect their sign and kind.

### compareTo()

```php
public function compareTo(Fraction $value): int
```

Compares with another fraction by exact value.

- **Parameters:** `$value` the fraction to compare with
- **Returns:** `-1` if this is smaller, `0` if equal, `1` if greater

### equals()

```php
public function equals(Fraction $value): bool
```

Tests whether two fractions have the same value.

- **Parameters:** `$value` the fraction to compare with
- **Returns:** `bool`

### isZero()

```php
public function isZero(): bool
```

Tests whether the fraction is zero.

- **Returns:** `bool`

### isInteger()

```php
public function isInteger(): bool
```

Tests whether the fraction is a whole number, which means its denominator is 1.

- **Returns:** `bool`

### sign()

```php
public function sign(): Sign
```

The sign of the fraction.

- **Returns:** `PHPMath\BigInteger\Sign`: `Positive`, `Negative` or `Zero`

## Rounding

Turn a fraction into a whole number. Each method returns a `BigInteger`.

### floor()

```php
public function floor(): BigInteger
```

The largest integer that is less than or equal to the fraction.

- **Returns:** `BigInteger`

### ceil()

```php
public function ceil(): BigInteger
```

The smallest integer that is greater than or equal to the fraction. A result of zero has no minus sign.

- **Returns:** `BigInteger`

### truncate()

```php
public function truncate(): BigInteger
```

The integer part, rounded toward zero. A result of zero has no minus sign.

- **Returns:** `BigInteger`

### round()

```php
public function round(): BigInteger
```

The nearest integer. Halves are rounded away from zero. A result of zero has no minus sign.

- **Returns:** `BigInteger`

## Conversion

Convert a fraction to a decimal string or a float.

### toDecimalString()

```php
public function toDecimalString(int $scale): string
```

Writes the fraction as a decimal with exactly `$scale` decimals, rounded half away from zero. A value that rounds to zero has no minus sign.

- **Parameters:** `$scale` the number of decimals, zero or more
- **Returns:** `string`
- **Throws:** [InvalidScaleException](errors.md) if the scale is negative

### toFloat()

```php
public function toFloat(): float
```

Returns the float that is nearest to the fraction. The result is correctly rounded for any size of numerator and denominator, and an exact tie goes to the float with an even last bit. A value above `PHP_FLOAT_MAX` that rounds up gives `INF` or `-INF`, subnormal results are rounded to the available precision, a value below half of the smallest subnormal gives `0.0`, and negative zero is never returned. See [Rounding and conversion](rounding.md) for examples.

- **Returns:** `float`
