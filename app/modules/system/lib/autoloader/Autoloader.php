<?php

namespace App\Modules\System\Autoloader;

use Bitrix\Catalog\Ebay\EbayXMLer;
use Exception;

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
	 * @param string $namespace
	 * @param string $dir
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
		}catch (Exception $exception)
		{
			echo $exception->getMessage();
		}
	}

	/**
	 * @param string $namespace
	 * @param string $className
	 * @throws Exception
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
				throw new Exception('Class ' . $className . ' doesnt exist. Please create file with this class');
			}
		}else
		{
			throw new Exception('Namespace ' . $namespace . ' doesnt exist in namespaces array. Please use Psr4Autoloader::addNamespace method for autoload classes');
		}
	}

	/**
	 * @return void
	 */
	public function registerApplicationNamespaces(): void
	{
		$namespaces = require_once $_SERVER['DOCUMENT_ROOT'] . "/app/namespaces.php";
		foreach($namespaces as $namespace)
		{
			$this->addNamespace($namespace);
		}
	}
}