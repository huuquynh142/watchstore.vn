<?php

    $router = $di->getRouter();

    $router->setDefaultModule("frontend");

    $router->add('/:module/:controller/:action/:params', [
        'module'     => 1,
        'controller' => 2,
        'action'     => 3,
        "params"     => 4,
    ]);

    $router->add('/frontend', [
        'module'     => 'frontend',
        'controller' => 'index',
        'action'     => 'index',
    ]);

    $router->add('/backend', [
        'module'     => 'backend',
        'controller' => 'index',
        'action'     => 'index',
    ]);

    $router->handle();


