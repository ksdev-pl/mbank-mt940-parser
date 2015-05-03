# mBank MT940 parser

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ksdev-pl/mbank-mt940-parser/master.svg?style=flat-square)](https://travis-ci.org/ksdev-pl/mbank-mt940-parser)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/ksdev-pl/mbank-mt940-parser.svg?style=flat-square)](https://scrutinizer-ci.com/g/ksdev-pl/mbank-mt940-parser/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/ksdev-pl/mbank-mt940-parser.svg?style=flat-square)](https://scrutinizer-ci.com/g/ksdev-pl/mbank-mt940-parser)

Parser for the mBank MT940 daily statement export file format.

## Install

Via Composer

``` bash
$ composer require ksdev/mbank-mt940-parser
```

## Usage

``` php
$parser = new Ksdev\Mt940Parser\Mt940Parser();
try {
    $statement = $parser->parse('path/to/MT940.txt');
}
catch (Exception $e) {
    //
}
```

## Statement structure

``` php
array(
    0 =>
        array(
            'generationDate'   => '071023',
            'accountNumber'    => '82114010100000579009005002',
            'statementNumber'  => '11',
            'openingBalance'   =>
                array(
                    'balance'  => 'D',
                    'date'     => '071023',
                    'currency' => 'EUR',
                    'amount'   => '959,45',
                ),
            'transactions'     =>
                array(
                    0 =>
                        array(
                            'valueDate'   => '071023',
                            'bookingDate' => '1023',
                            'balance'     => 'C',
                            'currency'    => 'EUR',
                            'amount'      => '2,21',
                            'code'        => 'TRF',
                            'description' => '973-MT PRZELEW NA RZECZ',
                            'details'     => '973 IBRE PRZELEW WEWNĘTRZNY; Z RACH.: 46114010100000579009004001; OD: KLIENT TESTOWY SP. Z O.O. WARSZAWA, UL. SENATORSKA 18; TYT.: ZWROT NADPŁATY ZA F-RĘ 30/2006; TNR: 145411008036753.010002',
                        ),
                    1 =>
                        array(
                            'valueDate'   => '071023',
                            'bookingDate' => '1023',
                            'balance'     => 'D',
                            'currency'    => 'EUR',
                            'amount'      => '16,36',
                            'code'        => 'TRF',
                            'description' => '944-PRZEL.KRAJ.WYCH.MT.ELX',
                            'details'     => '944 IBRE PRZELEW KRAJOWY; NA RACH.: 40106000760000390201994867; DLA: WYPOŻYCZALNIA PŁYT DVD PRZYGODA MONIUSZKI 7 56-328 BRONOWICE; TYT.: WYPOŻYCZENIE FILMÓW DVD; WALUTA: PLN; KWOTA: 86,20; KURS: 5,2691; TNR: 145410009337393.020001',
                        ),
                    2 =>
                        array(
                            'valueDate'   => '071023',
                            'bookingDate' => '1023',
                            'balance'     => 'D',
                            'currency'    => 'EUR',
                            'amount'      => '1,27',
                            'code'        => 'CHG',
                            'description' => '794-OPŁATY GRUPOWE',
                            'details'     => '794 OPŁATY GRUPOWE; TNR: 145420004413026.000005',
                        ),
                ),
            'closingBalance'   =>
                array(
                    'balance'  => 'D',
                    'date'     => '071023',
                    'currency' => 'EUR',
                    'amount'   => '974.87',
                ),
            'availableBalance' =>
                array(
                    'balance'  => 'D',
                    'date'     => '071023',
                    'currency' => 'EUR',
                    'amount'   => '974.87',
                ),
        ),
    1 =>
        array(...)
);
```

## Testing

``` bash
$ phpunit
```

## Word of advice

Analyze the code thoroughly before you use it and adjust it to your needs - this project was made for private use.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
