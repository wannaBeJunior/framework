<?php

namespace App\Modules\System\Configuration;

class Configuration
{
	protected array $configuration;

	public function __construct()
	{
		$this->configuration = require $_SERVER['DOCUMENT_ROOT'] . "/app/configuration.php";
	}

	public function has(string $entity) : bool
	{
		return isset($this->configuration[$entity]);
	}

	public function get(string $entity)
	{
		return $this->configuration[$entity];
	}

	public function getDatabaseConfiguration() : array
	{
		return $this->get('DB');
	}
}