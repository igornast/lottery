<?php


namespace App\Service\Saver;


use Symfony\Component\Filesystem\Filesystem;

class DataSaver
{
    const DIR_PATH = VAR_DIRECTORY . DIRECTORY_SEPARATOR . 'lottery_results';
    /**
     * @var Filesystem
     */
    private $fileSystem;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    public function save(array $items): void
    {
        $filename = sprintf('%s.%s', uniqid(), 'json');
        $json = json_encode($items);

        $this->fileSystem->mkdir(self::DIR_PATH, 0770);
        $this->fileSystem->dumpFile(self::DIR_PATH . DIRECTORY_SEPARATOR . $filename, $json);
    }
}