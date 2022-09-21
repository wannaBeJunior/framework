<?php

namespace App\Modules\System;

class Controller
{
	protected string $filePath;
	protected string $controllerType;
	protected string $action;
	protected ControllerInterface $controller;

	public function __construct(string $filePath, array $route)
	{
		$this->filePath = $filePath;
		$this->controllerType = $route['controller'];
		$this->action = $route['action'];
	}

	public function run()
	{
		try {
			$this->getControllerInstance();
			$this->callAction();
		}catch (\Exception $exception)
		{
			errorLog($exception->getMessage());
		}
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