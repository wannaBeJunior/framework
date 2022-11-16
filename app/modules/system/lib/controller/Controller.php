<?php

namespace App\Modules\System\Controller;

use App\Modules\System\Router\Route;

class Controller
{
	protected string $filePath;
	protected string $controllerType;
	protected string $action;
	protected ControllerInterface $controller;
	protected Route $route;

	public function setRoute(Route $route)
	{
		$this->route = $route;
	}

	public function run()
	{
		try {
			$this->setFilePath();
			$this->setControllerType();
			$this->setAction();
			$this->getControllerInstance();
			$this->callAction();
		}catch (\Exception $exception)
		{
			echo $exception->getMessage();
		}
	}

	public function setFilePath()
	{
		$this->filePath = '/app/controllers/' . $this->route->getController() . '.php';
	}

	public function setControllerType()
	{
		$this->controllerType = $this->route->getController();
	}

	public function setAction()
	{
		$this->action = $this->route->getAction();
	}

	public function getControllerInstance()
	{
		$className = 'App\\Controllers\\' . $this->controllerType;
		if(class_exists($className))
		{
			$this->controller = new $className();
		}else
		{
			throw new \Exception('Class ' . $className . ' doesnt exist. Please create this class in ' . $this->filePath);
		}
	}

	public function callAction()
	{
		if(method_exists($this->controller, $this->action))
		{
			$action = $this->action;
			($this->controller)->$action();
		}else
		{
			throw new \Exception('Method ' . $this->action . ' doesnt exist. Please create this class in ' . $this->filePath);
		}
	}
}