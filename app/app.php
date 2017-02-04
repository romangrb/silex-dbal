<?php

require_once __DIR__ . '/../vendor/autoload.php';


use MainApp\Controller\AppController;
use MainApp\Controller\CRUDController;

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

$app->register(new \MainApp\Provider\CRUDRepositoryProvider());

$app->register(new \MainApp\Provider\CRUDServiceProvider());

$app['message.controller'] = function() use($app) {
     return new MessageController($app['blog.message.service']);
 };
 
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['blog.controller'] = function() use($app) {
    return new AppController($app['blog.message.service']);
};

include __DIR__ . '/routing.php';

return $app;