<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

// load configuration
$config = require __DIR__ . '/config.php';

// set the configuration as a service
$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

// create the container
$serviceManager = new ServiceManager($dependencies);
$serviceManager->setAllowOverride(true);

return $serviceManager;
