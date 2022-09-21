<?php

namespace App\Modules\System;

class Application
{
	protected array $currRoute;

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
	}

	public function startRouter() : void
	{
		$router = new Router();
		$router->run();
		$this->currRoute = $router->getCurrentRoute();
	}

	public function exec()
	{
		$controllerFilePath = '/app/controllers/' . $this->currRoute['controller'] . '.php';
		$controller = new Controller($controllerFilePath, $this->currRoute);
		$controller->run();
	}
}