<?php

namespace Starpeace\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Starpeace\Console\Helpers\WorldConfig;

class WorldSettingsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'generate:world-settings-view';

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
        $testingEnabled = (bool) $input->getArgument('testing');

        define('TESTING', $testingEnabled);

        if ($testingEnabled) {
            define('TESTING_PATH', APP_PATH . '/Testing/');
        }

        $testingPath = APP_PATH . '/Testing/worldconfig.ini';
        $standardPath = BASE_SERVER_PATH . "\\worldconfig.ini";

        if (self::$DEBUG) {
            print_r([
               'Testing Enabled' => $testingEnabled ? 'TRUE' : 'FALSE',
                'Testing Path' => $testingPath,
                'Standard Path' => $standardPath,
            ]);
        }

        if (
            $testingEnabled &&
            !is_file($testingPath)
        ) {
            if (!is_dir(APP_PATH . '/Testing')) {
                @mkdir(APP_PATH . '/Testing');
            }
            @copy($standardPath, $testingPath);
        }

        $path = $testingEnabled ? $testingPath : $standardPath;

        if (!is_file($path)) {
            echo "The path [$path] is not a valid file.";
            exit;
        }

        $iniContents = @parse_ini_file($path);

        if (!empty($iniContents)) {
            $table = WorldConfig::processConfig($iniContents);
            if (is_dir(BASE_WEB_PATH . 'five\\0\\visual\\voyager\\newlogon')) {
                file_put_contents(BASE_WEB_PATH . 'five\\0\\visual\\voyager\\newlogon\\world_setting.htm', $table);
            }
        }
    }
}
