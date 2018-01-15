<?php
use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     *
     */
    include $config->application->vendor. 'autoload.php';
    $application = new \Phalcon\Mvc\Application($di);

    $application->setDI($di);


    // Register the installed modules
    $application->registerModules([
        'frontend' => [
            'className' => 'Multiple\Frontend\Module',
            'path'      => '../app/frontend/Module.php'
        ],
        'backend'  => [
            'className' => 'Multiple\Backend\Module',
            'path'      => '../app/backend/Module.php'
        ]
    ]);



   // echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());
    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
