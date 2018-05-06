<?php

    $router = $di->getRouter();

    $router->setDefaultModule("frontend");

    $router->add('/:module/:controller/:action/:params', [
        'module'     => 1,
        'controller' => 2,
        'action'     => 3,
        "params"     => 4,
    ]);

    $router->add('/trang-chu', [
        'module'     => 'frontend',
        'controller' => 'index',
        'action'     => 'index',
    ])->setName("trang-chu");

    $router->add('/tat-ca-san-pham', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
    ])->setName("tat-ca-san-pham");

    $router->add('/dang-nhap', [
        'module'     => 'frontend',
        'controller' => 'account',
        'action'     => 'login',
    ])->setName("dang-nhap");

    $router->add('/dang-ky', [
        'module'     => 'frontend',
        'controller' => 'account',
        'action'     => 'register',
    ])->setName("login");
    $router->add('/gio-hang', [
        'module'     => 'frontend',
        'controller' => 'shopcart',
        'action'     => 'index',
    ])->setName("gio-hang");

    $router->add('/backend', [
        'module'     => 'backend',
        'controller' => 'product',
        'action'     => 'index',
    ])->setName("backend");

    $router->handle();


