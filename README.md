# mBank MT940 parser

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Parser for the mBank MT940 daily statement export file format.

## Install

Via Composer

``` bash
$ composer require ksdev/mbank-mt940-parser
```

## Usage

``` php
$parser = new Mt940Parser();
try {
    $statement = $parser->parse('path/to/MT940.txt');
}
catch (\Exception $e) {
    //
}
```

## Testing

``` bash
$ phpunit
```

## Word of advice

Analyze the code thoroughly before you use it and adjust it to your needs - this project was made for private use.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
