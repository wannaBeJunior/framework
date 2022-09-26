<?php

namespace App\Modules\System;

class Container
{
	protected array $services;
	static protected Container $instance;

	private function __construct()
	{
		$this->services = [
			Router::class => fn() => new Router(),
			Controller::class => fn() => new Controller(),
			Configuration::class => fn() => new Configuration(),
			Db::class => fn() => new Db(self::get(Configuration::class)),
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
		return $this->services[$id]();
	}
}