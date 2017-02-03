<?php
define( 'PATH_ROOT', dirname( __DIR__ ) );
define( 'PATH_APP', PATH_ROOT . '/app' );

require PATH_ROOT . '/vendor/autoload.php';

$app = new Silex\Application();

$app['environment'] = getenv('ENVIRONMENT');

// require PATH_ROOT . '/config/' . $app['environment'] . '.php';
require PATH_ROOT . '/config/' . 'local' . '.php';
require PATH_APP . '/app.php';

$app->run();
// $app['environment'] === 'production' && $app['cache.enable'] ? $app['http_cache']->run() : $app->run();