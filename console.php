#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Starpeace\Console\Commands\WorldSettingsCommand;
use Starpeace\Console\Helpers\Device;

Device::defineOSVars();

$application = new Application();

// ... register commands
$application->add(new WorldSettingsCommand());

$application->run();

