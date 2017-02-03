<?php

namespace Blog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use \LogicException;
use Blog\Repository\MessageRepository;

class MessageRepositoryProvider implements ServiceProviderInterface
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

            return new MessageRepository($app['db']);
        };
    }

} 
