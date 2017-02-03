<?php

$app['debug']        = true;

$app['timezone']     = 'Europe/UK';

$app['cache.enable'] = false;	

$app['db.enable']    = true;

$app['db.options']   = [
	'driver'	=> 'pdo_mysql',
	'host'		=> '127.0.0.1',
	'dbname'	=> 'silex',
	'user'		=> 'root',
	'password'	=> ''
];