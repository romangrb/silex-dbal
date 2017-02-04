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
        $app["blog.message.repository"] = function ($app) {
            if(!isset($app['db'])) {
                throw new LogicException('You must register the DoctrineServiceProvider to use the MessageRepositoryProvider');
            }

            return new CRUDRepository($app['db']);
        };
    }

} 
