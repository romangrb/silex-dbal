<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MainApp\Controller\AppController;
use MainApp\Controller\FsController;

$app = new Silex\Application();

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/log_info.log',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'silex',
        'user'   => 'root',
        'password' => '',
        'host'   => 'localhost',
    ),
));

$app->register(new \MainApp\Provider\CRUDServiceProvider());

$app->register(new \MainApp\Provider\FsServiceProvider());

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['app.controller'] = function() use($app) {
    return new AppController($app['app.crud.service']);
};

$app['fs.controller'] = function() use($app) {
    return new FsController($app['app.crud.service'], $app['app.fs_helper.service']);
};

include __DIR__ . '/routing.php';

return $app;