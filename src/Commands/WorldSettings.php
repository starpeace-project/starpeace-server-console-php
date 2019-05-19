<?php

namespace Starpeace\Console\Commands;

use Starpeace\Console\Helpers\Device;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorldSettingsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'generate:world-settings-view';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a world settings view for the world logon page.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Creates a world settings view for the world logon page.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = BASE_SERVER_PATH . "\\wordldconfig.ini";
        $iniContents = @parse_ini_file($path);

        echo $path . PHP_EOL;

        echo Device::getOSSpecific() . '('.php_uname('s').')'. PHP_EOL;
    }
}