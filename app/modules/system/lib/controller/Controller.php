<?php

namespace App\Modules\System\Controller;

use App\Modules\System\Exceptions\ClassDoesntExistException;
use App\Modules\System\Exceptions\FileNotFoundException;
use App\Modules\System\Exceptions\MethodDoesntExistException;
use App\Modules\System\Router\Route;
use Exception;

class Controller
{
	protected string $filePath;
	protected string $controllerType;
	protected string $action;
	protected array $urlParameters;
	protected ControllerInterface $controller;

	public function __construct(Route $route)
	{
		$this->filePath = $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/' . $route->getController() . '.php';
		$this->controllerType = $route->getController();
		$this->action = $route->getAction();
		$this->urlParameters = $route->getMatches();
	}

	public function run()
	{
		try {
			$this->checkControllerFileExists($this->filePath);
			$this->getControllerInstance();
			$this->callAction();
		}catch (FileNotFoundException $exception)
		{
			echo '500 file not found';
			die();
		}catch (ClassDoesntExistException $exception)
		{
			echo '500 class doesnt exist';
			die();
		}catch (MethodDoesntExistException $exception)
		{
			echo '500 method doesnt exist';
			die();
		}catch (Exception $exception)
		{
			echo '500';
			die();
		}
	}

	/**
	 * @param string $path
	 * @throws FileNotFoundException
	 */
	private function checkControllerFileExists(string $path): void
	{
		if(!file_exists($path))
		{
			throw new FileNotFoundException('Controller file ' . $path . ' doesnt exists.');
		}
	}

	/**
	 * @throws ClassDoesntExistException
	 */
	public function getControllerInstance()
	{
		$className = 'App\\Controllers\\' . $this->controllerType;
		if(class_exists($className))
		{
			$this->controller = new $className();
		}else
		{
			throw new ClassDoesntExistException('Class ' . $className . ' doesnt exist. Please create this class in ' . $this->filePath);
		}
	}

	/**
	 * @throws MethodDoesntExistException
	 */
	public function callAction()
	{
		if(method_exists($this->controller, $this->action))
		{
			$action = $this->action;
			($this->controller)->$action($this->urlParameters);
		}else
		{
			throw new MethodDoesntExistException('Method ' . $this->action . ' doesnt exist. Please create this class in ' . $this->filePath);
		}
	}
}