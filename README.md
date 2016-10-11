# Specifications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pbmedia/specifications.svg?style=flat-square)](https://packagist.org/packages/pbmedia/specifications)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/specifications/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/specifications)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/specifications.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/specifications)
[![Total Downloads](https://img.shields.io/packagist/dt/pbmedia/specifications.svg?style=flat-square)](https://packagist.org/packages/pbmedia/specifications)

## Install

Via Composer

``` bash
$ composer require pbmedia/specifications
```

## Usage

This package lets you specifiy object, for example products.

```php
use Pbmedia\Specifications\HasSpecifications;
use Pbmedia\Specifications\Interfaces\CanBeSpecified;

class NotebookProduct implements CanBeSpecified {

    use HasSpecifications;

}
```

Set up ```Attribute``` and ```Score``` objects:

```php
use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\Score;

class DiskCapacityInGB implements Attribute
{
    public function getIdentifier()
    {
        return 'DiskCapacityInGB';
    }
}

class SizeInGB implements Score
{
    private $sizeInGB;

    public function __construct($sizeInGB)
    {
        $this->sizeInGB = $sizeInGB;
    }

    public function getValue()
    {
        return $this->sizeInGB;
    }
}
```

Now you can 'specify' you ```NotebookProduct``` like this:

```php
$macbookAir = new NotebookProduct;

$attribute = new DiskCapacityInGB;
$score = new SizeInGB(256);

// returns an instance of \Pbmedia\Specifications\Specifications
$specifications = $macbookAir->specifications();
$specifications->set($attribute, $score);
```

Take a look at the docblocks of ```Specifications.php``` to see what else you can do with it. This package also comes with a ```Matcher``` service which can sort collections of specificable objects based on 'criteria' you provide.

```php
$matcher = new \Pbmedia\Specifications\Matcher;

$matcher->addCandidates([
    $macbookAir, $macbookPro
]);

// just as a product itself, add 'criteria' to compare against:
$matcher->specifications()->set(
    new DiskCapacityInGB, new SizeInGB(512)
);

// get a collection of notebooks sorted based on which ones are most closely to the criteria.
$matcher->get();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email pascal@pascalbaljetmedia.com instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/pascalbaljet)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.