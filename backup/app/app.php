<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Blog\Controller\BlogController;
use Blog\Controller\MessageController;

$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'silex',
        'user'   => 'root',
        'password' => '',
        'host'   => 'localhost',
    ),
));

$app->register(new \Blog\Provider\MessageRepositoryProvider());

$app->register(new \Blog\Provider\MessageServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$app['message.controller'] = function() use($app) {
     return new MessageController($app['twig'], $app['blog.message.service']);
 };
 
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['blog.controller'] = function() use($app) {
    return new BlogController($app['twig'], $app['blog.message.service']);
};

include __DIR__ . '/routing.php';


return $app;