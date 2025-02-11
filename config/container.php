<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use MySchema\EventManager\LazyActionListener;
use Psr\EventDispatcher\ListenerProviderInterface;

// load configuration
$config = require __DIR__ . '/config.php';

// set the configuration as a service
$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

// create the container
$serviceManager = new ServiceManager($dependencies);
$serviceManager->setAllowOverride(TRUE);

// set the event listener service
$serviceManager->setService(ListenerProviderInterface::class, new LazyActionListener($serviceManager));

// wire in apps
$apps = [];
$dir = getcwd() . '/apps';
$filesystem = new Filesystem(new LocalFilesystemAdapter($dir));
foreach ($filesystem->listContents('') as $app) {
    if (! $app instanceof DirectoryAttributes) {
        continue;
    }

    $appName = $app->path();
    foreach ($filesystem->listContents($app->path()) as $appItem) {
        if (! $appItem instanceof FileAttributes) {
            continue;
        }

        if (FALSE === strpos($appItem->path(), 'config.php')) {
            continue;
        }

        $configFile = $dir . DIRECTORY_SEPARATOR . $appItem->path();
        $apps[$appName] = require $configFile;
    }
}

$serviceManager->setService('apps', $apps);

return $serviceManager;
