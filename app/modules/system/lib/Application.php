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
		spl_autoload_register('App\Modules\System\Autoloader::loadClass');
	}

	public function exec()
	{

	}
}