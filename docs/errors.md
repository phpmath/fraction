---
title: Errors and exceptions
intro: What can go wrong and how to catch it.
group: Reference
order: 70
---

## Library exceptions

Every exception the library throws on purpose implements the marker interface `PHPMath\Fraction\Exception\FractionException`, which extends `Throwable`. Catch it to handle any error from this library in one `catch` block. The library has three exceptions, all in the namespace `PHPMath\Fraction\Exception`, and each extends one standard PHP exception.

| Exception | Extends | Thrown when |
|---|---|---|
| `DivisionByZeroException` | `InvalidArgumentException` | A denominator, a divisor or a base is zero where that is not allowed. |
| `InvalidFormatException` | `InvalidArgumentException` | A string is not a valid number. |
| `InvalidScaleException` | `InvalidArgumentException` | `toDecimalString()` gets a negative scale. |

The exceptions are `final`, so you cannot extend them. Catch `FractionException` for all of them, or a PHP parent class such as `InvalidArgumentException` if you also want to catch errors from other code.

## When DivisionByZeroException is thrown

This table lists every case where a method throws it, with the message that goes with it.

| Method | Condition | Message |
|---|---|---|
| `of()`, `fromString()` | The denominator is zero. | `The denominator cannot be zero.` |
| `divide()` | The divisor is zero. | `Cannot divide by zero.` |
| `reciprocal()` | The fraction is zero. | `Cannot divide by zero.` |
| `pow()` | The fraction is zero and the exponent is negative. | `Cannot raise zero to a negative power.` |

A zero denominator or divisor is an invalid argument, so this exception extends `InvalidArgumentException`. It is not PHP's built-in `DivisionByZeroError`, so a `catch (DivisionByZeroError)` block does not catch it.

## When InvalidFormatException is thrown

A string is rejected when it is not a whole number or, for `fromString()`, a fraction or a decimal. The message has the form `Invalid number format: "text"`.

| Method | Condition |
|---|---|
| `of()` | A string argument is not an integer, such as `'abc'`, `'1.5'` or `'1/2'`. |
| `fromString()` | The text is not an integer, a fraction or a decimal, such as `'.5'`, `'1e3'`, `' 1'`, `'1/2/3'` or `''`. |

## When InvalidScaleException is thrown

`toDecimalString()` throws it when the scale is below zero. The message has the form `The scale must be zero or greater, -1 given.`

## Handling untrusted input

Catch `FractionException` to handle every error from the library at once. Bad format and zero denominator can both come from user input.

```php
use PHPMath\Fraction\Exception\FractionException;
use PHPMath\Fraction\Fraction;

foreach (['3/4', '1/0', 'abc'] as $input) {
    try {
        echo Fraction::fromString($input), "\n";
    } catch (FractionException $exception) {
        echo $exception::class, ': ', $exception->getMessage(), "\n";
    }
}

// 3/4
// PHPMath\Fraction\Exception\DivisionByZeroException: The denominator cannot be zero.
// PHPMath\Fraction\Exception\InvalidFormatException: Invalid number format: "abc"
```
