<?php

namespace Blog\Provider;

use Blog\Service\MessageService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use LogicException;

class MessageServiceProvider implements ServiceProviderInterface
{
    /**
     * Méthode appelée lors de l'association du service à un container.
     *
     * @param Container $app
     * @return MessageService
     */
    public function register(Container $app)
    {
        $app['blog.message.service'] = function ($app) {
            if(!isset($app['db'])) {
                throw new LogicException('You must register the DoctrineServiceProvider to use the MessageServiceProvider');
            }

            if(!isset($app['blog.message.repository'])) {
                throw new LogicException('You must register the MessageRepositoryProvider to use the MessageServiceProvider');
            }

            return new MessageService($app['db'], $app['blog.message.repository']);
        };
    }
}