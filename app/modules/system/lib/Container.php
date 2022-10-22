<?php

namespace App\Modules\System;

class Container
{
	protected array $services;
	protected array $cachedServices;
	static protected Container $instance;

	private function __construct()
	{
		$this->services = [
			Router::class => fn() => new Router(),
			Controller::class => fn() => new Controller(),
			Configuration::class => fn() => new Configuration(),
			MySqlDb::class => fn() => new MySqlDb(self::get(Configuration::class)),
			Session::class => fn () => new Session(),
			User::class => fn() => new User(self::get(MySqlDb::class), self::get(Session::class)),
			HttpContext::class => fn() => new HttpContext(),
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
		if(isset($this->cachedServices[$id]))
		{
			return $this->cachedServices[$id];
		}
		$this->cachedServices[$id] = $this->services[$id]();
		return $this->cachedServices[$id];
	}
}