<?php


namespace App\Service\Fetcher\Type;


use App\Constant\Lottery;
use DOMDocument;
use DOMElement;

class EuromillionFetcherType implements LotteryFetcherTypeInterface
{
    const URL = 'https://www.elgordo.com/results/euromillonariaen.asp';
    const LOTTERY_BODY_CLASS = 'body_game ee';
    const LOTTERY_DATE_CLASS = 'c';
    const DIV_NUMBER_CLASS = 'num';
    const DIV_ADDITIONAL_NUM_CLASS = 'esp';

    const IDX_ADDITIONAL_NUMBER_INT = 0;
    const IDX_ADDITIONAL_NUMBER_TXT = 1;


    public function fetch(): array
    {
        $data = [];
        $dom = new DOMDocument();
        $dom->loadHTML(self::URL);

        /**
         * @var int $idx
         * @var DOMElement $div
         */
        foreach ($dom->getElementsByTagName('div') as $idx => $div) {
            $date = null;
            $class = $div->getAttribute('class');

            if ($class !== self::LOTTERY_BODY_CLASS) {
                continue;
            }
            $numbers = [];
            $additionalNumbers = [];

            /** @var DOMElement $bodyDiv */
            foreach ($div->getElementsByTagName('div') as $bodyDiv) {
                $bodyDivClass = $bodyDiv->getAttribute('class');

                if ($bodyDivClass === self::LOTTERY_DATE_CLASS) {
                    $date = $bodyDiv->textContent;
                    continue;
                }

                if ($bodyDivClass === self::DIV_NUMBER_CLASS) {
                    $numbers[] = $bodyDiv->nodeValue;
                }

                if (strpos($bodyDivClass, self::DIV_ADDITIONAL_NUM_CLASS) !== false) {
                    $int = $bodyDiv->getElementsByTagName('span')[self::IDX_ADDITIONAL_NUMBER_INT]->nodeValue;
                    $txt = $bodyDiv->getElementsByTagName('span')[self::IDX_ADDITIONAL_NUMBER_TXT]->nodeValue;
                    $additionalNumbers[] = ['int' => $int, 'text' => $txt];
                }
            }

            $data[] = ['date' => $date, 'result' => ['numbers' => $numbers, 'additional' => $additionalNumbers]];
        }

        return $data;
    }

    public function supported(): string
    {
        return Lottery::EUROMILLIONARIEN;
    }
}