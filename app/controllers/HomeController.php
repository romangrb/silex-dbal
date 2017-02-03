<?php
namespace Controller;
	
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController implements ControllerProviderInterface {

	public function connect( Application $app )
	{
		$controller = $app['controllers_factory'];
		
		$controller->get( '/', [$this, 'action'] )->bind('home');
		$controller->before( [$this, 'before'] );

		return $controller;
	}

	public function action( Application $app )
	{
		return $app['twig']->render('home.twig');
	}

	public function before( Request $request, Application $app )
	{
	}

}