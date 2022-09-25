<?php

namespace App\Modules\System;

class Application
{
	protected Route $currRoute;

	public function run() : void
	{
		$this->init();
		$this->exec();
	}

	public function init() : void
	{
		$this->registerAutoloader();
		$this->startRouter();
	}

	public function registerAutoloader() : void
	{
		$autoloader = new Psr4Autoloader();
		$autoloader->register();
		$autoloader->addNamespace('App\Modules\System\\', 'app/modules/system/lib/');
		$autoloader->addNamespace('App\Controllers\\', 'app/controllers/');
	}

	public function startRouter() : void
	{
		$router = Container::getInstance()->get(Router::class);
		$router->run();
		$this->currRoute = $router->getCurrentRoute();
	}

	public function exec()
	{
		Session::start();
		$controller = Container::getInstance()->get(Controller::class);
		$controller->setRoute($this->currRoute);
		$controller->run();
	}
}