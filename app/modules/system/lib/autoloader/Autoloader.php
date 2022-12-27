<?php

namespace App\Modules\System\Autoloader;

use App\Modules\System\Exceptions\ClassDoesntExistException;
use App\Modules\System\Exceptions\FileNotFoundException;
use App\Modules\System\Exceptions\NamespaceDoesntExistException;

class Autoloader
{

	protected array $namespaces;

	/**
	 * @return void
	 */
	public function register(): void
	{
		spl_autoload_register([$this, 'loadClass']);
	}

	/**
	 * @param EntityInterface $entity
	 * @return void
	 */
	public function addNamespace(EntityInterface $entity): void
	{
		$namespace = trim($entity->getNamespace(), '\\') . '\\';

		$dir = trim($entity->getDir(), DIRECTORY_SEPARATOR) . '/';

		if(!isset($this->namespaces[$namespace]))
		{
			$this->namespaces[$namespace] = [];
		}

		$this->namespaces[$namespace][] = $dir;
	}

	/**
	 * @param string $className
	 * @return void
	 */
	public function loadClass(string $className): void
	{
		try {
			$lastNamespaceSeparatorPosition = strrpos($className, '\\') + 1;
			$namespace = substr($className, 0, $lastNamespaceSeparatorPosition);
			$className = substr($className, $lastNamespaceSeparatorPosition, strlen($className));
			$this->requireMappedFile($namespace, $className);
		}catch (NamespaceDoesntExistException $exception)
		{
			$exception
				->getLogger()
				->error($exception->getMessage(), 'autoloader.log');
			echo "500 namespace doesnt exist";
			die();
		}catch (ClassDoesntExistException $exception)
		{
			$exception
				->getLogger()
				->error($exception->getMessage(), 'autoloader.log');
			echo "500 class doesnt exist";
			die();
		}
	}

	/**
	 * @param string $namespace
	 * @param string $className
	 * @throws ClassDoesntExistException
	 * @throws NamespaceDoesntExistException
	 */
	public function requireMappedFile(string $namespace, string $className): void
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
				throw new ClassDoesntExistException('Class ' . $className . ' doesnt exist. Please create file with this class');
			}
		}else
		{
			throw new NamespaceDoesntExistException('Namespace ' . $namespace . ' doesnt exist in namespaces array. Please use Autoloader::addNamespace method for autoload classes');
		}
	}

	/**
	 * @return void
	 */
	public function registerApplicationNamespaces(): void
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . "/app/namespaces.php";
		if(!file_exists($path))
		{
			throw new FileNotFoundException($path . " doesnt exist");
		}
		$namespaces = require_once $_SERVER['DOCUMENT_ROOT'] . "/app/namespaces.php";
		foreach($namespaces as $namespace)
		{
			$this->addNamespace($namespace);
		}
	}
}