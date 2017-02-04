<?php

namespace MainApp\Provider;

use MainApp\Service\CRUDService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use LogicException;

class CRUDServiceProvider implements ServiceProviderInterface
{
    
    public function register(Container $app)
    {
        $app['app.crud.service'] = function ($app) {
            if(!isset($app['db'])) {
                throw new LogicException('The DoctrineServiceProvider does not registered, the CRUDServiceProvider could be use');
            }

            if(!isset($app['app.crud.repository'])) {
                throw new LogicException('The MessageRepositoryProvider does not registered, the CRUDServiceProvider could be use');
            }

            return new CRUDService($app['db'], $app['app.crud.repository']);
        };
    }
}