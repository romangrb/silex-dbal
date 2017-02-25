<?php

namespace MainApp\Controller;

use \Silex\Application,
    \Silex\ControllerProviderInterface;

class Person implements ControllerProviderInterface
{
    public function connect(Application $application)
    {
        $this->app   = $application;
        $controllers = $this->app['controllers_factory'];

        $controllers->get(
            '/all', 
            array($this, 'getAllBlogPosts')
        );
        

        return $controllers;
    }
}