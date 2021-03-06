<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '123456',
        'dbname'      => 'watches_online',
        'charset'     => 'utf8',
    ],
//    'database' => [
//        'adapter'     => 'Mysql',
//        'host'        => 'sql212.byethost.com',
//        'username'    => 'b4_22077174',
//        'password'    => '14121996',
//        'dbname'      => 'watches_online',
//        'charset'     => 'utf8',
//    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/backend/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/backend/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => BASE_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'vendor'         => BASE_PATH . '/vendor/',
        'logDir'       	 => BASE_PATH . '/log/',


        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/customerinfo.phtml entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ]
]);
