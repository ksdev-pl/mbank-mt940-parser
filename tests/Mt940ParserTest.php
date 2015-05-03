<?php

namespace Ksdev\Mt940Parser\Test;

use Ksdev\Mt940Parser\Mt940Parser;

class Mt940ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseIph()
    {
        $parser = new Mt940Parser();
        $statement = $parser->parse(__DIR__ . '/MT940_IPH.txt');

        $this->assertEquals('100604', $statement[0]['generationDate']);
        $this->assertEquals('45114011370000310211001001', $statement[0]['accountNumber']);
        $this->assertEquals('107', $statement[0]['statementNumber']);
        $this->assertEquals([
            'balance'  => 'C',
            'date'     => '100604',
            'currency' => 'PLN',
            'amount'   => '0,00'
        ], $statement[0]['openingBalance']);
        $this->assertEquals([
            'valueDate'   => '100604',
            'bookingDate' => '0604',
            'balance'     => 'C',
            'currency'    => 'PLN',
            'amount'      => '6979,18',
            'code'        => 'TRF',
            'description' => '911-TRANSAKCJA IPH',
            'details'     => '911 TRANSAKCJA IPH; ID IPH: XX000000000014; Z RACH.: 04105014611000002345271577; OD: GWARANT-CYNKOWANIE SPÓŁKA Z O.O. UL.CIEPŁOWNICZA 27 98-300 WIELUŃ; TYT.: 210F1163; DATA STEMPLA: 02.06.2010; TNR: 154961063737599.110001'
        ], $statement[0]['transactions'][0]);
        $this->assertEquals([
            'valueDate'   => '100604',
            'bookingDate' => '0604',
            'balance'     => 'D',
            'currency'    => 'PLN',
            'amount'      => '15,00',
            'code'        => 'CHG',
            'description' => '794-OPŁATY GRUPOWE',
            'details'     => '794 OPŁATY GRUPOWE; TNR: 154960005384269.330001'
        ], $statement[0]['transactions'][23]);
    }

    public function testParseMultipleAccounts()
    {
        $parser = new Mt940Parser();
        $statement = $parser->parse(__DIR__ . '/MT940_multiple_accounts.txt');

        $this->assertEquals('071023', $statement[0]['generationDate']);
        $this->assertEquals('46114010100000579009004001', $statement[0]['accountNumber']);
        $this->assertEquals('11', $statement[0]['statementNumber']);
        $this->assertEquals([
            'balance'  => 'C',
            'date'     => '071023',
            'currency' => 'PLN',
            'amount'   => '8985995772,25'
        ], $statement[0]['openingBalance']);
        $this->assertEquals([
            'balance'  => 'C',
            'date'     => '071023',
            'currency' => 'PLN',
            'amount'   => '8987999030,37'
        ], $statement[0]['closingBalance']);
        $this->assertEquals([
            'balance'  => 'C',
            'date'     => '071023',
            'currency' => 'PLN',
            'amount'   => '8987999030,37'
        ], $statement[0]['availableBalance']);
        $this->assertEquals([
            'valueDate'   => '071023',
            'bookingDate' => '1023',
            'balance'     => 'D',
            'currency'    => 'PLN',
            'amount'      => '6,62',
            'code'        => 'CHG',
            'description' => '843-OPŁ. POCZTOWA. NORM',
            'details'     => '843 OPŁATA POCZTOWA: NORM; TNR: 145411008025459.000001'
        ], $statement[0]['transactions'][0]);
        $this->assertEquals([
            'valueDate'   => '071023',
            'bookingDate' => '1023',
            'balance'     => 'D',
            'currency'    => 'PLN',
            'amount'      => '120,00',
            'code'        => 'CHG',
            'description' => '794-OPŁATY GRUPOWE',
            'details'     => '794 OPŁATY GRUPOWE; TNR: 145420004413026.000003'
        ], $statement[0]['transactions'][37]);

        $this->assertEquals('071023', $statement[6]['generationDate']);
        $this->assertEquals('82114010100000579009005002', $statement[6]['accountNumber']);
        $this->assertEquals('11', $statement[6]['statementNumber']);
        $this->assertEquals([
            'balance'  => 'D',
            'date'     => '071023',
            'currency' => 'EUR',
            'amount'   => '959,45'
        ], $statement[6]['openingBalance']);
        $this->assertEquals([
            'balance'  => 'D',
            'date'     => '071023',
            'currency' => 'EUR',
            'amount'   => '492,89'
        ], $statement[6]['closingBalance']);
        $this->assertEquals([
            'balance'  => 'D',
            'date'     => '071023',
            'currency' => 'EUR',
            'amount'   => '492,89'
        ], $statement[6]['availableBalance']);
        $this->assertEquals([
            'valueDate'   => '071023',
            'bookingDate' => '1023',
            'balance'     => 'C',
            'currency'    => 'EUR',
            'amount'      => '2,21',
            'code'        => 'TRF',
            'description' => '973-MT PRZELEW NA RZECZ',
            'details'     => '973 IBRE PRZELEW WEWNĘTRZNY; Z RACH.: 46114010100000579009004001; OD: KLIENT TESTOWY SP. Z O.O. WARSZAWA, UL. SENATORSKA 18; TYT.: ZWROT NADPŁATY ZA F-RĘ 30/2006; TNR: 145411008036753.010002'
        ], $statement[6]['transactions'][0]);
        $this->assertEquals([
            'valueDate'   => '071023',
            'bookingDate' => '1023',
            'balance'     => 'D',
            'currency'    => 'EUR',
            'amount'      => '1,27',
            'code'        => 'CHG',
            'description' => '794-OPŁATY GRUPOWE',
            'details'     => '794 OPŁATY GRUPOWE; TNR: 145420004413026.000005'
        ], $statement[6]['transactions'][6]);

        $this->assertEquals('071023', $statement[7]['generationDate']);
        $this->assertEquals('55114010100000579009005003', $statement[7]['accountNumber']);
        $this->assertEquals('11', $statement[7]['statementNumber']);
        $this->assertEquals([
            'balance'  => 'C',
            'date'     => '071023',
            'currency' => 'USD',
            'amount'   => '144,46'
        ], $statement[7]['openingBalance']);
        $this->assertEquals([
            'balance'  => 'C',
            'date'     => '071023',
            'currency' => 'USD',
            'amount'   => '144,46'
        ], $statement[7]['closingBalance']);
        $this->assertEquals([
            'balance'  => 'C',
            'date'     => '071023',
            'currency' => 'USD',
            'amount'   => '144,46'
        ], $statement[7]['availableBalance']);
    }
}
