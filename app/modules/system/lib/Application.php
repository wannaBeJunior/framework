<?php

namespace App\Modules\System;

use App\Modules\System\Autoloader\Autoloader;
use App\Modules\System\Container\Container;
use App\Modules\System\Controller\Controller;
use App\Modules\System\Router\Route;
use App\Modules\System\Router\Router;
use App\Modules\System\Session\Session;

class Application
{
	protected Route $currRoute;

	public function run() : void
	{
		$this->initialization();
		$this->execution();
	}

	public function initialization() : void
	{
		$this->registerAutoloader();
		$this->startRouter();
	}

	public function registerAutoloader() : void
	{
		$autoloader = new Autoloader();
		$autoloader->register();
		$autoloader->registerApplicationNamespaces();
	}

	public function startRouter() : void
	{
		$router = Container::getInstance()->get(Router::class);
		$router->run();
		$this->currRoute = $router->getCurrentRoute();
	}

	public function execution()
	{
		Session::start();
		$controller = Container::getInstance()->get(Controller::class);
		$controller->setRoute($this->currRoute);
		$controller->run();
	}
}