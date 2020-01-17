<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use Symfony\Component\Console\Application;

define('VAR_DIRECTORY', __DIR__.DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'');

$application = new Application();
$application->add(new \App\Command\SaveLotteryDataCommand());
$application->run();