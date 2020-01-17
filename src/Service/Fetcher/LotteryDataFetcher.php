<?php


namespace App\Service\Fetcher;


use App\Constant\Lottery;
use App\Service\Fetcher\Type\EuroJackpotFetcherType;
use App\Service\Fetcher\Type\EuromillionFetcherType;
use App\Service\Fetcher\Type\LotteryFetcherTypeInterface;
use App\Service\Fetcher\Type\LottoFetcherType;
use Exception;

class LotteryDataFetcher
{
    /**
     * @var array
     */
    private $fetchers;

    public function __construct()
    {
        $this->fetchers[Lottery::LOTTO] = new LottoFetcherType();
        $this->fetchers[Lottery::EUROJACKPOT] = new EuroJackpotFetcherType();
        $this->fetchers[Lottery::EUROMILLIONARIEN] = new EuromillionFetcherType();
    }

    /**
     * @param string $lotteryId
     * @return array
     * @throws Exception
     */
    public function fetchFor(string $lotteryId): array
    {
        $fetcher = $this->getFetcherType($lotteryId);

        return $fetcher->fetch();
    }

    /**
     * @param string $lotteryId
     * @return LotteryFetcherTypeInterface
     * @throws Exception
     */
    private function getFetcherType(string $lotteryId): LotteryFetcherTypeInterface
    {
        $type = $this->fetchers[$lotteryId] ?? null;

        if (!$type instanceof LotteryFetcherTypeInterface) {
            throw new Exception(sprintf('Type for %s, not implemented.', $lotteryId));
        }

        return $type;
    }


}