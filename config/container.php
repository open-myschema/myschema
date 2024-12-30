<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use MySchema\Application\LazyActionListener;
use Psr\EventDispatcher\ListenerProviderInterface;

// load configuration
$config = require __DIR__ . '/config.php';

// set the configuration as a service
$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

// create the container
$serviceManager = new ServiceManager($dependencies);
$serviceManager->setAllowOverride(true);

// set the event listener service
$serviceManager->setService(ListenerProviderInterface::class, new LazyActionListener($serviceManager));

return $serviceManager;
