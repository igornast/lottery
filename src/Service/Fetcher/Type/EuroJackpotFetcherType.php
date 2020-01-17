<?php


namespace App\Service\Fetcher\Type;


use App\Constant\Lottery;
use DOMDocument;
use DOMElement;

class EuroJackpotFetcherType implements LotteryFetcherTypeInterface
{
    const URL = 'https://www.lotto.pl/eurojackpot/wyniki-i-wygrane';
    const RESULT_CLASS = 'wynik';
    const DIV_CLASS = 'resultnumber';

    const COLUMN_LOTTERY_ID = 0;
    const COLUMN_LOTTERY_DATE = 1;
    const COLUMN_RESULT_TD = 2;

    public function fetch(): array
    {
        $data = [];
        $dom = new DOMDocument();
        $dom->loadHTML(self::URL);

        /**
         * @var int $idx
         * @var DOMElement $tr
         */
        foreach ($dom->getElementsByTagName('tr') as $idx => $tr) {
            $lotteryId = null;
            $class = $tr->getAttribute('class');

            if ($class !== self::RESULT_CLASS) {
                continue;
            }

            $lotteryId = $tr->getElementsByTagName('td')[self::COLUMN_LOTTERY_ID]->textContent;
            $date = $tr->getElementsByTagName('td')[self::COLUMN_LOTTERY_DATE]->textContent;
            $result = [];

            /** @var DOMElement $resultTd */
            $resultTd = $tr->getElementsByTagName('td')[self::COLUMN_RESULT_TD];
            /** @var DOMElement $div */
            foreach ($resultTd->getElementsByTagName('div') as $div) {
                $numbers = [];
                $class = $div->getAttribute('class');

                if ($class !== self::DIV_CLASS) {
                    continue;
                }

                foreach ($div->getElementsByTagName('span') as $pos => $span) {
                    $numbers[] = $span->nodeValue;
                }

                $result[] = $numbers;
            }

            $data[$lotteryId][] = ['date' => $date, 'result' => $result];
        }

        return $data;
    }

    public function supported(): string
    {
        return Lottery::EUROJACKPOT;
    }
}