<?php

namespace App\Modules\System\Router;

class Route
{
	private string $path;
	private string $controller;
	private string $action;
	private array $matches;
	private string $method;

	public function __construct(string $path, string $controller, string $action, string $method = 'ALL')
	{
		$this->path = $path;
		$this->controller = $controller;
		$this->action = $action;
		$this->method = $method;
	}

	/**
	 * @param array $matches
	 */
	public function setMatches(array $matches): void
	{
		$this->matches = $matches;
	}

	/**
	 * @param string $path
	 */
	public function setPath(string $path): void
	{
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getController(): string
	{
		return $this->controller;
	}

	/**
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @return array
	 */
	public function getMatches(): array
	{
		return $this->matches;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}
}