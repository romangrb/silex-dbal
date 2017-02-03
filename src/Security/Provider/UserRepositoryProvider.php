<?php

namespace Security\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use \LogicException;
use Security\Repository\UserRepository;

class UserRepositoryProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app["blog.user.repository"] = function ($app) {
            if(!isset($app['db'])) {
                throw new LogicException('You must register the DoctrineServiceProvider to use the UserRepositoryProvider');
            }

            return new UserRepository($app['db']);
        };
    }

} 
