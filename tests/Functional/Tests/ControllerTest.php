<?php
namespace Functional\Tests;

use Silex\WebTestCase;
use Silex\Provider\DoctrineServiceProvider;
use Blog\Provider\MessageRepositoryProvider;

class ControllerTest extends WebTestCase
{
    protected $app;

    /**
     * @before
     */
    public function createApplication()
    {
        $this->app = require __DIR__ . '/../../../app/app.php';
        $this->app['debug'] = true;
        unset($this->app['exception_handler']);

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
    public function shouldReturnTestMessage() {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('p:contains("message de test")'));
    }
}