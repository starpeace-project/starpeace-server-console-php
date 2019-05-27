<?php

namespace Starpeace\Console\Commands;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tightenco\Collect\Support\Collection;

class IPFraudLog extends Command
{
    protected static $defaultName = 'analyse:ips';

    private $interfaceLogPath;

    private $fileData = [];
    private $groupedByDate = [];
    private $groupedByIp = [];
    private $groupedByAlias = [];
    private $aliases = [];
    private $tableData = [];

    private $output;

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
        $this->output = $output;
        define_testing($this->isTesting($input), ['TESTING_PATH' => path_join(BASE_TESTING_PATH, 'Clients')]);

        $this->fileData = file_lines_multi(TESTING ? TESTING_PATH : $this->interfaceLogPath, $this->getLogFiles(),
            true);

        $this->processResults();
        $this->gatherAliases();
        $this->getSubscriptionDetails();
        //$this->appendToFileData();

        // fileData array now has all needed data.

        $this->groupByDate();

        foreach ($this->groupedByDate as $date => &$entries) {
            $entries = array_multi_group_by_key($entries, 'ip_address', true);
        }

        foreach ($this->groupedByDate as $date => &$dateEntries) {
            foreach ($dateEntries as $ip => &$ipEntries) {
                $ipEntries = array_multi_group_by_key($ipEntries, 'player_alias', true);
                $ipEntries = array_unique(array_keys($ipEntries));
                foreach ($ipEntries as $key => $alias) {
                    $ipEntries[$alias] = $this->aliases[$alias];
                    unset($ipEntries[$key]);
                }
            }
        }

        $this->filterOutSingles();

        $this->filterAllPaid($output);

        $this->filterOneFreeOnePaid();

        $this->unsetEmpties();

        $this->tabulariseData();

        $this->showTable();
    }

    protected function appendToFileData()
    {
        foreach ($this->fileData as &$entry) {
            $entry = array_merge($entry, $this->aliases[$entry['player_alias']]);
        }
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


    protected function processResults()
    {
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
                    $entry['logon'] = Carbon::createFromFormat('y-m-d H:i:s A',
                        implode(' ', [$fileKey, $line[2], $line[3]]))->format('d-m-Y H:i:s');
                } catch (\Exception $e) {
                    // Silence
                }

                try {
                    $entry['logoff'] = Carbon::createFromFormat('y-m-d H:i:s A',
                        implode(' ', [$fileKey, $line[4], $line[5]]))->format('d-m-Y H:i:s');
                } catch (\Exception $e) {
                    // Silence
                }

                $entries[] = $entry;
            }
        }

        $this->fileData = array_values($entries);
    }

    protected function gatherAliases()
    {
        $this->aliases = array_keys(array_multi_group_by_key($this->fileData, 'player_alias', true));
    }

    protected function getSubscriptionDetails()
    {
        if (TESTING && is_file(path_join(TESTING_PATH, 'subs.json'))) {
            $this->aliases = json_decode(file_get_contents(path_join(TESTING_PATH, 'subs.json')), true);
        } else {
            $url = 'https://starpeaceonline.info/api/console/subscription/data/' . json_encode($this->aliases);
            $this->aliases = json_decode(file_get_contents($url), true);
        }


        if (TESTING) {
            file_put_contents(path_join(TESTING_PATH, 'subs.json'), json_encode($this->aliases));
        }
    }

    protected function groupByDate()
    {
        $this->groupedByDate = array_multi_group_by_key($this->fileData, 'date', true);
    }

    protected function groupByIp()
    {
        $this->groupedByIp = array_multi_group_by_key($this->fileData, 'ip_address', true);
    }

    protected function groupAliases()
    {
        foreach ($this->groupedByIp as $ip => $entries) {
            $aliases = [];
            foreach ($entries as $entry) {
                $aliases[] = $entry['player_alias'];
            }

            foreach (array_unique($aliases) as $alias) {
                $this->groupedByAlias[$ip][$alias] = [];
            }
        }
    }

    protected function filterOutSingles()
    {
        foreach ($this->groupedByDate as $date => $ips) {
            foreach ($ips as $ip => $users) {
                if (count($users) < 2) {
                    unset($this->groupedByDate[$date][$ip]);
                }
            }
        }
    }

    protected function filterAllPaid()
    {
        foreach ($this->groupedByDate as $date => $ips) {
            foreach ($ips as $ip => $users) {
                $paid = 0;
                foreach ($users as $alias => $user) {
                    if (!$this->outOfDate($user['paid_planets'])) {
                        $paid++;
                    }
                }
                if ($paid === count($users)) {
                    unset($this->groupedByDate[$date][$ip]);
                }
            }
        }
    }

    protected function filterOneFreeOnePaid()
    {
        foreach ($this->groupedByDate as $date => $ips) {
            foreach ($ips as $ip => $users) {
                if (count($users) == 2) {
                    $oneValid = false;
                    $oneInValid = false;

                    foreach ($users as $alias => $user) {
                        if (!$this->outOfDate($user['paid_planets'])) {
                            $oneValid = true;
                        } else {
                            $oneInValid = true;
                        }
                    }
                    if ($oneValid && $oneInValid) {
                        unset($this->groupedByDate[$date][$ip]);
                    }
                }
            }
        }
    }

    protected function getCarbonPaidPlanets($date)
    {
        if (strlen($date) == 10) {
            $date = Carbon::createFromFormat('m/d/Y', $date);
        } else {
            $date = Carbon::createFromFormat('n/j/Y', $date);
        }

        return $date;
    }

    protected function outOfDate($date)
    {
        if ($date == "none") {
            return true;
        }

        return Carbon::now()->diffInDays($this->getCarbonPaidPlanets($date)) < 1;
    }

    protected function unsetEmpties()
    {
        foreach ($this->groupedByDate as $date => $ips) {
            if (empty($ips)) {
                unset($this->groupedByDate[$date]);
            }
        }
    }

    protected function tabulariseData()
    {
        foreach ($this->groupedByDate as $date => $ips) {
            foreach ($ips as $ip => $users) {
                foreach ($users as $alias => $user) {
                    $row['date'] = $date;
                    $row['ip'] = $ip;
                    $row['alias'] = $alias;
                    $row['paid_planets'] = $alias . ': ' . $user['paid_planets'];
                    $this->tableData[] = $row;
                }
            }
        }

        $rows = new Collection($this->tableData);

        $ipList = $rows->pluck('ip')->unique('ip');

        $builtRows = [];
        foreach ($ipList as $ip) {
            $row = [];
            $row[] = $ip;

            $row[] = implode(PHP_EOL, array_unique($rows->where('ip', $ip)->pluck('date')->toArray()));
            $row[] = implode(PHP_EOL, array_unique($rows->where('ip', $ip)->pluck('alias')->toArray()));
            $row[] = implode(PHP_EOL, array_unique($rows->where('ip', $ip)->pluck('paid_planets')->toArray()));
            $builtRows[] = $row;
        }

        $this->tableData = $builtRows;
    }

    protected function showTable()
    {
        $table = new Table($this->output);
        $table->setHeaders(['IP Address', 'Dates', 'Aliases', 'Paid Planet Access']);
        $table->setRows($this->tableData);
        $table->render();
    }
}