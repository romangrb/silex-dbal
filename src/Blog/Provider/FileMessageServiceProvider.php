<?php
namespace Blog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Blog\Entity\Message;
use Doctrine\Common\Collections\ArrayCollection;
use \DateTime; 

class FileMessageServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{

        /**
     * Méthode appelée lors de l'association du service à un container.
     *
     * @param Container $app
     * @return mixed
     */
    public function register(Container $app)
    {
        $app['blog.message.file'] = function () {
            return "";
        };
    }

    /**
     * Méthode appelée lors du lancement de l'application.
     *
     * @param Application $app
     * @return mixed
     */
    public function boot(Application $app)
    {
     $file = "../ressources/messages.csv";
     $app['blog.message.file']=$file;
     
    }
    
}