<?php

namespace App\Modules\System;

class Route
{
	protected string $controller;
	protected string $action;
	protected array $matches;

	public function __construct(string $controller, string $action, array $matches = [])
	{
		$this->controller = $controller;
		$this->action = $action;
		$this->matches = $matches;
	}

	public function setMatches(array $matches) : void
	{
		$this->matches = $matches;
	}

	public function getController() : string
	{
		return $this->controller;
	}

	public function getAction() : string
	{
		return $this->action;
	}
}