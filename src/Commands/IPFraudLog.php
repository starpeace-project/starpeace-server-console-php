<?php

namespace Starpeace\Console\Commands;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IPFraudLog extends Command
{
    protected static $defaultName = 'analyse:ips';

    private $interfaceLogPath;

    private $fileData = [];

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->interfaceLogPath = path_join('D:', 'FIVE', 'Data', 'Logs', 'FIVEINTERFACESERVER');
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Find multiple user names on the same up address.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Find multiple user names on the same up address.')
            ->addArgument('testing', InputArgument::OPTIONAL, 'Run in test mode.');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        define_testing($input->getArgument('testing'), ['TESTING_PATH' => path_join(BASE_TESTING_PATH, 'Clients')]);

        $this->fileData = file_lines_multi(TESTING ? TESTING_PATH : $this->interfaceLogPath, $this->getLogFiles());

        $this->fileData = array_map(function ($fileKey, $lines) {
            $fileKey = explode(' ', $fileKey);
            $fileKey = end($fileKey);
            $fileKey = explode('.', $fileKey);
            $fileKey = current(reset($fileKey));

            $entries = [];
            foreach ($lines as $line) {
                $line = explode(' ', $line);

                $entry = [
                    'player_alias' => $line[0],
                    'ip_address' => $line[1],
                    'logon' => Carbon::createFromFormat('y-m-d h:i:s A', implode(' ', [$fileKey, $line[2], $line[3]])),
                    'logoff' => Carbon::createFromFormat('y-m-d h:i:s A', implode(' ', [$fileKey, $line[4], $line[5]])),
                ];

                $entries[] = $entry;
            }

            return $entries;

        }, $this->fileData);

        dd($this->fileData);
    }

    protected function getLogFiles()
    {
        if (TESTING) {
            $files = strncmp_get_files(TESTING_PATH, 'Clients');
            if (empty($files)) {
                $files = strncmp_get_files($this->interfaceLogPath, 'Clients');
                check_dir(TESTING_PATH);
                copy_files($this->interfaceLogPath, TESTING_PATH, $files);
            }
        } else {
            $files = strncmp_get_files($this->interfaceLogPath, 'Clients');
        }

        if (empty($files)) {
            throw new \Exception('No log files to process.');
        }

        return $files;
    }

}