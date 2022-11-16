<?php

namespace App\Modules\System\Autoloader;

class Entity implements EntityInterface
{
	private string $namespace;
	private string $dir;

	public function __construct(string $namespace, string $dir)
	{
		$this->namespace = $namespace;
		$this->dir = $dir;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getDir(): string
	{
		return $this->dir;
	}
}