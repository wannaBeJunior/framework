<?php

namespace App\Modules\System;

class Router
{
	protected array $routes;
	protected string $currentURL;
	protected const ROUTE_PLACEHOLDERS_TO_REGEXP = [
		'{int}' => '([0-9]*)',
		'{string}' => '([a-zA-Z-!@#$%^&*()]*)',
	];
	protected array $currentRoute;

	public function run()
	{
		try {
			$this->setRoutes();
			$this->setCurrentURL();
			$this->currentRoute = $this->getRouteByURL();
		}catch (\Exception $exception)
		{
			errorLog($exception->getMessage());
		}
	}

	public function setRoutes()
	{
		$this->routes = require $_SERVER['DOCUMENT_ROOT'] . '/app/routes.php';
	}

	public function setCurrentURL()
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = explode('?', $url);
		$this->currentURL = $url[0];
	}

	public function getRouteByURL() : array
	{
		foreach ($this->routes as $route => $metaData)
		{
			$route = $this->replacePlaceholders($route);
			if(preg_match('/' . str_replace('/', '\/', trim($route, '/')) . '/', $this->currentURL, $matches))
			{
				return $metaData;
			}
		}
		throw new \Exception('Route ' . $this->currentURL . ' doesnt exist. Please add this route to the app/routes.php');
	}

	public function replacePlaceholders(string $route) : string
	{
		foreach (self::ROUTE_PLACEHOLDERS_TO_REGEXP as $placeholder => $regex)
		{
			if(stripos($route, $placeholder))
			{
				$route = str_replace($placeholder, $regex, $route);
			}
		}
		return $route;
	}

	public function getCurrentRoute()
	{
		return $this->currentRoute;
	}
}