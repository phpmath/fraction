# fraction

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![CI][ico-ci]][link-ci]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP library to work with exact, immutable rational numbers. It is built on top of
[phpmath/biginteger](https://github.com/phpmath/biginteger), so numerators and denominators
can be arbitrarily large.

## Documentation

The full documentation is at https://phpmath.github.io/fraction/. Every release is described on the [releases page](https://github.com/phpmath/fraction/releases).

## Install

```bash
composer require phpmath/fraction
```

## Requirements

- PHP 8.4 or newer
- The `gmp` extension
- `phpmath/biginteger`, which Composer installs for you

## Usage

``` php
use PHPMath\Fraction\Fraction;

$a = Fraction::of(1, 3);
$b = Fraction::fromString('0.125'); // 1/8

echo $a->add($b);            // 11/24
echo $a->toDecimalString(5); // 0.33333
```

## Features

- Exact, immutable rational numbers, always stored in lowest terms
- Arbitrarily large numerators and denominators
- Arithmetic, comparison, powers and reciprocals
- `floor()`, `ceil()`, `round()` and `truncate()`
- Exact decimal strings and a correctly rounded `toFloat()`

Every exception implements `FractionException`, see the documentation for the full list.

## Development

```bash
composer test
composer test-coverage
composer analyse
composer check-style
composer fix-style
```

## Contributing

All contributions are welcome. Open an issue or a pull request on GitHub. Please follow the coding standard and add tests.

## Security

If you discover a security issue, please create an issue in the issue tracker.

## Credits

- [Walter Tamboer](https://github.com/waltertamboer)
- [All contributors](https://github.com/phpmath/fraction/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/phpmath/fraction.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-ci]: https://img.shields.io/github/actions/workflow/status/phpmath/fraction/ci.yml?branch=master&style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/phpmath/fraction.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/phpmath/fraction
[link-ci]: https://github.com/phpmath/fraction/actions/workflows/ci.yml
[link-downloads]: https://packagist.org/packages/phpmath/fraction/stats
