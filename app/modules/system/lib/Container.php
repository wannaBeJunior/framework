<?php

namespace App\Modules\System;

class Container
{
	protected array $services;
	static protected Container $instance;

	private function __construct()
	{
		$this->services = [
			Router::class => new Router(),
			Psr4Autoloader::class => new Psr4Autoloader(),
		];
	}

	static public function getInstance() : Container
	{
		if(!isset(self::$instance))
		{
			self::$instance = new Container();
		}
		return self::$instance;
	}

	public function get(string $id)
	{
		return $this->services[$id];
	}
}