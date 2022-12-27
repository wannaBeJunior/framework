<?php

namespace App\Modules\System\Router;

use App\Modules\System\Exceptions\FileNotFoundException;
use App\Modules\System\Exceptions\RouteNotFoundException;

class Router
{
	protected array $routes;
	protected string $currentURL;
	protected Route $route;
	protected array $matches = [];

	public function run(): void
	{
		try {
			$this->setRoutes();
			$this->setCurrentURL();
			$this->route = $this->getRouteByURL();
		}catch (FileNotFoundException $exception)
		{
			echo '500';
			die();
		}catch (RouteNotFoundException $exception)
		{
			echo '404';
			die();
		}
	}

	/**
	 * @throws FileNotFoundException
	 */
	private function setRoutes(): void
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . '/app/routes.php';
		if(!file_exists($path))
		{
			throw new FileNotFoundException('File ' . $path . ' was not found');
		}
		$this->routes = require $_SERVER['DOCUMENT_ROOT'] . '/app/routes.php';
	}

	private function setCurrentURL(): void
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = explode('?', $url);
		$this->currentURL = $url[0];
	}

	/**
	 * @return Route
	 * @throws RouteNotFoundException
	 */
	private function getRouteByURL(): Route
	{
		foreach ($this->routes as $route)
		{
			$this->replacePlaceholders($route);
			if($this->isCurrentUrl($route))
			{
				unset($this->matches[0]);
				$route->setMatches($this->matches);
				return $route;
			}
		}
		throw new RouteNotFoundException('Route ' . $this->currentURL . ' doesnt exist. Please add this route to the app/routes.php');
	}

	/**
	 * @param string $route
	 * @return string
	 */
	private function replacePlaceholders(Route $route): void
	{
		$path = preg_replace_callback('/{([a-zA-Z]+)}/', function($matches) {
			return '(?<' . $matches[1] . '>[a-zA-Z0-9_]+)';
		}, $route->getPath());
		$route->setPath($path);
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	private function isCurrentUrl(Route $route): bool
	{
		$path = trim($route->getPath(), '/');
		$pattern = '/^' . str_replace('/', '\/', $path) . '$/';
		if(preg_match($pattern, trim($this->currentURL, '/'), $this->matches))
		{
			if($route->getMethod() != 'ALL')
			{
				return $_SERVER['REQUEST_METHOD'] === $route->getMethod();
			}
			return true;
		}
		return false;
	}

	/**
	 * @return Route
	 */
	public function getRoute(): Route
	{
		return $this->route;
	}
}