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

    $router->add('/san-pham/tat-ca-san-pham/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        "params"     => 1,
    ])->setName("tat-ca-san-pham");

    $router->add('/san-pham/chi-tiet-san-pham/:params', [
        'module'     => 'frontend',
        'controller' => 'productDetail',
        'action'     => 'productDetail',
        "params"     => 1,
    ])->setName("chi-tiet-san-pham");

    $router->add('/dang-nhap', [
        'module'     => 'frontend',
        'controller' => 'account',
        'action'     => 'login',
    ])->setName("dang-nhap");

    $router->add('/san-pham/hang-san-xuat/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'producer',
        'params'    => 1
    ]);

    $router->add('/san-pham/san-pham-nam/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        'type_id'    => '1',
        'params'    => 1
    ]);
    $router->add('/san-pham/san-pham-nu/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        'type_id'    => '2',
        'params'    => 1
    ]);
    $router->add('/san-pham/san-pham-the-thao/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        'type_id'    => '3',
        'params'    => 1
    ]);
    $router->add('/san-pham/san-pham-sang-trong/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        'type_id'    => '4',
        'params'    => 1
    ]);
    $router->add('/san-pham/san-pham-giao-duc/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        'type_id'    => '5',
        'params'    => 1
    ]);

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


