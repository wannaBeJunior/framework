<?php

namespace App\Modules\System;

class Autoloader
{
	static public function loadClass(string $className) : void
	{
		try {
			$filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . self::getClassFilePathByNamespace($className);
			if(!file_exists($filePath))
			{
				throw new \Exception();
			}
			require_once $filePath;
		}catch (\Exception $exception)
		{
			echo 'Не могу подключить файл с классом ' . $className;
		}
	}

	static public function getClassFilePathByNamespace(string $namespace) : string
	{
		$filePath = strtolower($namespace);
		$filePath = str_replace('\\', '/', $filePath);
		$className = basename($filePath);
		return str_replace($className, 'lib/' . ucfirst($className) . '.php', $filePath);
	}
}