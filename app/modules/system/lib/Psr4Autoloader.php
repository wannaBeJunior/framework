<?php

namespace App\Modules\System;

class Psr4Autoloader
{

	protected array $namespaces;
	public function register() : void
	{
		spl_autoload_register([$this, 'loadClass']);
	}

	public function addNamespace(string $namespace, string $dir) : void
	{
		$namespace = trim($namespace, '\\') . '\\';

		$dir = trim($dir, DIRECTORY_SEPARATOR) . '/';

		if(!isset($this->namespaces[$namespace]))
		{
			$this->namespaces[$namespace] = [];
		}

		$this->namespaces[$namespace][] = $dir;
	}

	public function loadClass(string $className) : void
	{
		try {
			$lastNamespaceSeparatorPosition = strrpos($className, '\\') + 1;
			$namespace = substr($className, 0, $lastNamespaceSeparatorPosition);
			$className = substr($className, $lastNamespaceSeparatorPosition, strlen($className));
			$this->requireMappedFile($namespace, $className);
		}catch (\Exception $exception)
		{
			errorLog($exception->getMessage());
			die();
		}
	}

	public function requireMappedFile(string $namespace, string $className) : void
	{
		if(isset($this->namespaces[$namespace]))
		{
			$isRequired = false;
			foreach ($this->namespaces[$namespace] as $dir)
			{
				$classFilePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir . $className . '.php';
				if(file_exists($classFilePath))
				{
					$isRequired = require_once $classFilePath;
					break;
				}
			}
			if(!$isRequired)
			{
				throw new \Exception('Class ' . $className . ' doesnt exist. Please create file with this class');
			}
		}else
		{
			throw new \Exception('Namespace ' . $namespace . ' doesnt exist in namespaces array. Please use Psr4Autoloader::addNamespace method for autoload classes');
		}
	}
}