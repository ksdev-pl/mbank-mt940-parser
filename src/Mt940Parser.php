<?php

namespace Ksdev\Mt940Parser;

class Mt940Parser
{
    /**
     * Parse the MT940 file format and return it as an readable array
     *
     * @param string $filePath
     *
     * @return array
     *
     * @throws \Exception
     */
    public function parse($filePath)
    {
        $preparedFile = $this->prepareFile($filePath);
        $statement = $this->parseContent($preparedFile);

        return $statement;
    }

    /**
     * Convert the file content into an array structure
     *
     * @param string $filePath
     *
     * @return array
     *
     * @throws \RuntimeException  If the file cannot be opened
     */
    private function prepareFile($filePath)
    {
        $file = new \SplFileObject($filePath);

        $i = 0;
        $isBlockOpen = false;
        $preparedArray = [];
        foreach ($file as $line) {
            $strippedLine = str_replace(["\r", "\n"], '', $line);

            if ($strippedLine == chr(45) . chr(3)) {
                $i++;
                $isBlockOpen = false;
                continue;
            }

            if ($strippedLine == chr(1)) {
                $isBlockOpen = true;
                continue;
            }

            if ($isBlockOpen) {
                if (preg_match('/^:.{2,3}:/', $line)) {
                    $preparedArray[$i][] = $line;
                }
                else {
                    $lastKey = end(array_keys($preparedArray[$i]));
                    $preparedArray[$i][$lastKey] .= $line;
                }
            }
        }

        return $preparedArray;
    }

    /**
     * Convert the prepared array into an easily readable form
     *
     * @param array $preparedArray
     *
     * @return array
     *
     * @throws \Exception
     */
    private function parseContent($preparedArray)
    {
        $statement = [];
        foreach ($preparedArray as $key => $accountBlock) {
            foreach ($accountBlock as $tagLine) {
                if (preg_match('/^:(.{2,3}):(.*)/s', $tagLine, $matches)) {
                    $tagNum = $matches[1];
                    $tagContent = $matches[2];

                    switch ($tagNum) {
                        case '20':
                            $generationDate = $this->parseStatementIdentifier($tagContent);
                            $statement[$key]['generationDate'] = $generationDate;
                            break;
                        case '25':
                            $accountNumber = $this->parseAccountNumber($tagContent);
                            $statement[$key]['accountNumber'] = $accountNumber;
                            break;
                        case '28C':
                            $statementNumber = $this->parseStatementNumber($tagContent);
                            $statement[$key]['statementNumber'] = $statementNumber;
                            break;
                        case '60F':
                            $openingBalance = $this->parseBalance($tagContent);
                            $statement[$key]['openingBalance'] = $openingBalance;
                            break;
                        case '62F':
                            $closingBalance = $this->parseBalance($tagContent);
                            $statement[$key]['closingBalance'] = $closingBalance;
                            break;
                        case '64':
                            $availableBalance = $this->parseBalance($tagContent);
                            $statement[$key]['availableBalance'] = $availableBalance;
                            break;
                        case '61':
                            $transaction = $this->parseTransaction($tagContent);
                            $statement[$key]['transactions'][] = $transaction;
                            break;
                        case '86':
                            $details = $this->parseTransactionDetails($tagContent);
                            $lastKey = end(array_keys($statement[$key]['transactions']));
                            $statement[$key]['transactions'][$lastKey]['details'] = $details;
                            break;
                    }
                }
                else {
                    throw new \Exception('Invalid format of tag line');
                }
            }
        }

        return $statement;
    }

    /**
     * @param string $tagContent
     *
     * @return string
     *
     * @throws \Exception
     */
    private function parseStatementIdentifier($tagContent)
    {
        if (preg_match('/ST(\d{6})/', $tagContent, $matches)) {
            $generationDate = $matches[1];

            return $generationDate;
        }

        throw new \Exception('Invalid format of statement identifier');
    }

    /**
     * @param string $tagContent
     *
     * @return string
     *
     * @throws \Exception
     */
    private function parseAccountNumber($tagContent)
    {
        if (preg_match('/(\d{26})/', $tagContent, $matches)) {
            $accountNumber = $matches[1];

            return $accountNumber;
        }

        throw new \Exception('Invalid format of account number');
    }

    /**
     * @param string $tagContent
     *
     * @return string
     *
     * @throws \Exception
     */
    private function parseStatementNumber($tagContent)
    {
        if (preg_match('/(\d+)\//', $tagContent, $matches)) {
            $statementNumber = $matches[1];

            return $statementNumber;
        }

        throw new \Exception('Invalid format of statement number');
    }

    /**
     * @param string $tagContent
     *
     * @return array
     *
     * @throws \Exception
     */
    private function parseBalance($tagContent)
    {
        if (preg_match('/([CD])(\d{6})(\w{3})([\d,]+)/', $tagContent, $matches)) {
            $balance  = $matches[1];
            $date     = $matches[2];
            $currency = $matches[3];
            $amount   = $matches[4];

            return compact('balance', 'date', 'currency', 'amount');
        }

        throw new \Exception('Invalid format of balance');
    }

    /**
     * @param string $tagContent
     *
     * @return array
     *
     * @throws \Exception
     */
    private function parseTransaction($tagContent)
    {
        if (preg_match('/(\d{6})(\d{4})([CD])([A-Z])([\d,]+)N(\w{3}).*\n(.+)/', $tagContent, $matches)) {
            $valueDate      = $matches[1];
            $bookingDate    = $matches[2];
            $balance        = $matches[3];
            $currencyLetter = $matches[4];
            $amount         = $matches[5];
            $code           = $matches[6];
            $description    = mb_convert_encoding($matches[7], 'UTF-8', 'ISO-8859-2');

            $currencies = [
                'N' => 'PLN',
                'R' => 'EUR',
                'D' => 'USD'
            ];
            if (isset($currencies[$currencyLetter])) {
                $currency = $currencies[$currencyLetter];
            }
            else {
                throw new \Exception('Invalid format of transaction currency');
            }

            return compact('valueDate', 'bookingDate', 'balance', 'currency', 'amount', 'code', 'description');
        }

        throw new \Exception('Invalid format of transaction');
    }

    /**
     * @param string $tagContent
     *
     * @return string
     */
    private function parseTransactionDetails($tagContent)
    {
        return mb_convert_encoding(str_replace(["\r", "\n"], '', $tagContent), 'UTF-8', 'ISO-8859-2');
    }
}
