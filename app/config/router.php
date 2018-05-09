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

    $router->add('/san-pham/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        "params"     => 1,
    ])->setName("tat-ca-san-pham");
    $router->add('/product-search/:params', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'searchProduct',
        "params"     => 1,
    ])->setName("tim-kiem-san-pham");
    $router->add('/san-pham/san-pham-nam', [
        'module'     => 'frontend',
        'controller' => 'product',
        'action'     => 'index',
        "type_id"     => 1,
    ])->setName("san-pham-nam");

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


