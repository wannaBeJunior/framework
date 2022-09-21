<?php

namespace App\Modules\System;

class Application
{
	public function run()
	{
		$this->init();
		$this->exec();
	}

	public function init()
	{
		self::registerAutoloader();
	}

	static public function registerAutoloader()
	{
		$autoloader = new Psr4Autoloader();
		$autoloader->register();
	}

	public function exec()
	{

	}
}