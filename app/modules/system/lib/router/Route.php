<?php

namespace App\Modules\System\Router;

class Route
{
	private string $path;
	private string $controller;
	private string $action;
	private array $matches;

	public function __construct(string $path, string $controller, string $action)
	{
		$this->path = $path;
		$this->controller = $controller;
		$this->action = $action;
	}

	/**
	 * @param array $matches
	 */
	public function setMatches(array $matches): void
	{
		$this->matches = $matches;
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
}