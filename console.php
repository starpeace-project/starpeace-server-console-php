#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Starpeace\Console\Commands\WorldSettings;
use Starpeace\Console\Helpers\Device;
use Starpeace\Console\Commands\IPFraudLog;

Device::defineOSVars();

$application = new Application();

// ... register commands
$application->add(new WorldSettings());
$application->add(new IPFraudLog());

$application->run();

