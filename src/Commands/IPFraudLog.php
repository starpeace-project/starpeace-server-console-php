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
    private $groupedByIp = [];
    private $groupedByAlias = [];

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
        define_testing($this->isTesting($input), ['TESTING_PATH' => path_join(BASE_TESTING_PATH, 'Clients')]);

        $this->fileData = file_lines_multi(TESTING ? TESTING_PATH : $this->interfaceLogPath, $this->getLogFiles(), true);

        $entries = [];
        foreach ($this->fileData as $fileKey => $lines) {
            $fileKey = explode(' ', $fileKey);
            $fileKey = end($fileKey);
            $fileKey = explode('.', $fileKey);
            $fileKey = reset($fileKey);


            foreach ($lines as $line) {
                $line = explode(' ', $line);

                if (count($line) == 8) {
                    $line[1] = implode('.', [$line[0], $line[1]]);
                    unset($line[0]);
                    $line = array_values($line);
                }

                if (count($line) < 7) {
                    continue;
                }

                $entry = [
                    'player_alias' => $line[0],
                    'ip_address' => $line[1],
                ];

                try {
                    $entry['date'] = Carbon::createFromFormat('y-m-d', $fileKey)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Silence
                }

                try {
                    $entry['logon'] = Carbon::createFromFormat('y-m-d H:i:s A', implode(' ', [$fileKey, $line[2], $line[3]]))->format('d-m-Y H:i:s');
                } catch (\Exception $e) {
                    // Silence
                }

                try {
                    $entry['logoff'] = Carbon::createFromFormat('y-m-d H:i:s A', implode(' ', [$fileKey, $line[4], $line[5]]))->format('d-m-Y H:i:s');
                } catch (\Exception $e) {
                    // Silence
                }

                $entries[] = $entry;
            }
        }

        $this->fileData = array_values($entries);
        $this->groupByIp();
        $this->groupAliases();
        dd($this->groupedByAlias);
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

    protected function isTesting(InputInterface $input)
    {
        return !empty($input->getArgument('testing'));
    }

    protected function groupByIp()
    {
        $ips = [];

        foreach ($this->fileData as $entry) {
            try {
                dump($entry);
                $ips[$entry['ip_address']][] = $entry;
            } catch (\Exception $e) {
                dd($entry, $e);
            }

        }

        $this->groupedByIp = $ips;
    }

    protected function groupAliases()
    {
        foreach ($this->groupedByIp as $ip => $entries) {
            $aliases = [];
            foreach ($entries as $entry) {
                $aliases[] = $entry['player_alias'];
            }

            $this->groupedByAlias[] = [$ip => array_unique($aliases)];
        }
    }
}