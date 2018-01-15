<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
);
$loader->registerNamespaces([
    'App\\Controllers' => $config->application->controllersDir,
    'App\\Models' => $config->application->modelsDir,
    'App\\Library' => $config->application->libraryDir
]);
$loader->register();
