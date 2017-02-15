<?php

namespace MainApp\Provider;

use MainApp\Service\FsService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use LogicException;

class FsServiceProvider implements ServiceProviderInterface
{
    
    public function register(Container $app)
    {
        $app['app.fs_helper.service'] = function ($app) {
            if(!isset($app)) {
                throw new LogicException('The App does not registered, the FsServiceProvider could be use');
            }
            
            return new FsService();
        };
    }
}