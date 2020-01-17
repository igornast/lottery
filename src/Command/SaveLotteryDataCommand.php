<?php


namespace App\Command;


use App\Constant\Lottery;
use App\Service\Fetcher\LotteryDataFetcher;
use App\Service\Saver\DataSaver;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SaveLotteryDataCommand extends Command
{
    protected static $defaultName = 'app:lottery:save-data';
    /**
     * @var LotteryDataFetcher
     */
    private $fetcher;
    /**
     * @var DataSaver
     */
    private $saver;

    public function __construct()
    {
        parent::__construct();
        $this->fetcher = new LotteryDataFetcher();
        $this->saver = new DataSaver();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = [];

        foreach ($this->getLotteriesToFetch() as $lotteryId) {
            $items[] = ['type' => $lotteryId, 'results' => $this->fetcher->fetchFor($lotteryId)];
        }

        if (sizeof($items) < 1) {
            return;
        }

        $this->saver->save($items);
        return;
    }

    /**
     * @return string[]
     */
    private function getLotteriesToFetch(): array
    {
        return [Lottery::LOTTO, Lottery::EUROJACKPOT, Lottery::EUROMILLIONARIEN];
    }
}