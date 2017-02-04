<?php

namespace Blog\Provider;

use Security\Service\UserService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use LogicException;

class UserServiceProvider implements ServiceProviderInterface
{
    /**
     * Méthode appelée lors de l'association du service à un container.
     *
     * @param Container $app
     * @return UserService
     */
    public function register(Container $app)
    {
        $app['blog.user.service'] = function ($app) {
            if(!isset($app['db'])) {
                throw new LogicException('You must register the DoctrineServiceProvider to use the UserServiceProvider');
            }

            if(!isset($app['blog.user.repository'])) {
                throw new LogicException('You must register the UserRepositoryProvider to use the UserServiceProvider');
            }

            return new UserService($app['db'], $app['blog.user.repository']);
        };
    }
} 
