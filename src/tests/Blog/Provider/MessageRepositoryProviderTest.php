<?php
namespace Blog\Provider;

use PHPUnit\Framework\TestCase;
use Silex\Application;
use \LogicException;

class MessageRepositoryProviderTest extends TestCase
{
    public $app;

    /**
     * @before
     */
    public function setup()
    {
        $this->app = new Application();
    }

    /**
     * @test
     * @expectedException LogicException
     */
    public function registerShouldThrowLogicException() {

        $this->app->register(new MessageRepositoryProvider());

        $this->app['blog.message.repository']->fetchAll();
    }
} 
