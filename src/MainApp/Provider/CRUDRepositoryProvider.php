<?php

namespace MainApp\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use \LogicException;
use MainApp\Repository\CRUDRepository;

class CRUDRepositoryProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app["app.crud.repository"] = function ($app) {
            if(!isset($app['db'])) {
                throw new LogicException('The DoctrineServiceProvider is not registered, the MessageRepositoryProvider could be not be use');
            }

            return new CRUDRepository($app['db']);
        };
    }

} 
