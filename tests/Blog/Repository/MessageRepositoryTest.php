<?php

namespace Blog\Repository;

use Blog\Provider\MessageRepositoryProvider;
use PHPUnit\Framework\TestCase;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

class MessageRepositoryTest extends TestCase
{
    public $app;

    /**
     * @before
     */
    public function setupFixture() {

        $this->app = new Application();

        $this->app->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver' => 'pdo_mysql',
                'dbname' => 'silex',
                'user'   => 'silex',
                'password'     => 'silex',
                'host'   => 'localhost',
            ),
        ));

        $this->app['db']->executeQuery('TRUNCATE messages;');
        $this->app['db']->executeQuery('INSERT INTO messages (text, created_at) VALUES ("message de test", now());');

        $this->app->register(new MessageRepositoryProvider());
    }

    /**
     * @after
     */
    public function teardownFixture() {
        $this->app['db']->executeQuery('TRUNCATE messages;');
    }

    /**
     * @test
     */
    public function shouldHaveMessage() {
        $this->assertCount(1, $this->app['blog.message.repository']->fetchAll());
    }
} 
