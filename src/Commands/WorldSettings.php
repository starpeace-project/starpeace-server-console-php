<?php

namespace Starpeace\Console\Commands;

use Starpeace\Console\Helpers\WorldConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorldSettings extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'generate:world-settings-view';

    private $fileName = 'worldconfig.ini';

    private static $DEBUG = true;

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a world settings view for the world logon page.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Creates a world settings view for the world logon page.')
            ->addArgument('testing', InputArgument::OPTIONAL, 'Run in test mode.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $testingEnabled = (bool)$input->getArgument('testing');

        define_testing($input->getArgument('testing'), ['TESTING_PATH' => path_join(APP_PATH, 'Testing')]);

        $testingPath = APP_PATH . '/Testing/worldconfig.ini';
        $standardPath = BASE_SERVER_PATH . "\\worldconfig.ini";


        if (
            $testingEnabled &&
            !is_file($testingPath)
        ) {
            check_dir(path_join(APP_PATH, 'Testing'));
            copy_files(path_join(APP_PATH, 'Testing'), TESTING_PATH, $this->fileName);
        }

        $path = $testingEnabled ? $testingPath : $standardPath;

        if (!is_file($path)) {
            echo "The path [$path] is not a valid file.";
            exit;
        }

        $iniContents = @parse_ini_file($path);

        if (!empty($iniContents)) {
            $table = WorldConfig::processConfig($iniContents);
            if (is_dir(path_join(BASE_WEB_PATH, 'five', '0', 'visual', 'voyager', 'newlogon'))) {
                $output->writeln('Output directory exists.');
                file_put_contents(path_join(BASE_WEB_PATH, 'five', '0', 'visual', 'voyager', 'newlogon', 'world_setting.htm'), $table);
                $output->writeln('File written');
            }
        }
    }

    protected function getIniFileContents()
    {
        if (TESTING) {
            $iniContents = @parse_ini_file(path_join(TESTING_PATH, $this->fileName));
            if (empty($iniContents)) {
                copy_files(BASE_SERVER_PATH, TESTING_PATH, $this->fileName);
                $iniContents = @parse_ini_file(path_join(BASE_SERVER_PATH, $this->fileName));
            }
        } else {
            $iniContents = @parse_ini_file(path_join(BASE_SERVER_PATH, $this->fileName));
        }

        return $iniContents;
    }
}
