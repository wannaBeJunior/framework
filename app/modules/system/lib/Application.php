<?php

namespace App\Modules\System;

use App\Modules\System\Autoloader\Autoloader;
use App\Modules\System\Controller\Controller;
use App\Modules\System\Router\Route;
use App\Modules\System\Router\Router;
use App\Modules\System\Session\Session;

class Application
{
	protected Route $route;

	public function run(): void
	{
		$this->initialization();
		$this->execution();
	}

	public function initialization(): void
	{
		$this->registerAutoloader();
		$this->startRouter();
		Session::start();
	}

	public function registerAutoloader(): void
	{
		$autoloader = new Autoloader();
		$autoloader->register();
		$autoloader->registerApplicationNamespaces();
	}

	public function startRouter(): void
	{
		$router = new Router();
		$router->run();
		$this->route = $router->getRoute();
	}

	public function execution()
	{
		$controller = new Controller($this->route);
		$controller->run();
	}
}