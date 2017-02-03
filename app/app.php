<?php

date_default_timezone_set( $app['timezone'] );

// Define requirements

use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\MonologServiceProvider;

// Get Routes

require PATH_APP . '/routes.php';

// Registers services

if ( $app['cache.enable'] === true ) {
	$app->register(new HttpCacheServiceProvider([
		'http_cache.cache_dir' => PATH_ROOT . '/var/cache/http/'
	]));
}

if ( $app['db.enable'] === true ) {
	$app->register(new DoctrineServiceProvider());
}

$app->register(new SessionServiceProvider());

$app->register(new TwigServiceProvider(), [
	'twig.options' => [
		'cache'            => $app['cache.enable'],
		'strict_variables' => true
	],
	'twig.path'    => [PATH_APP . '/views/']
]);

$app->register(new MonologServiceProvider(), [
	'monolog.name'    => 'APP',
	'monolog.level'   => 300, // Warning
	'monolog.logfile' => PATH_ROOT . '/var/log/' . $app['environment'] . '.log'
]);

$app['session']->start();