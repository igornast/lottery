<?php


namespace App\Service\Fetcher\Type;


interface LotteryFetcherTypeInterface
{
    public function fetch(): array ;

    public function supported(): string;
}