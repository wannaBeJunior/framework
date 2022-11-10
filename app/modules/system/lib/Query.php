<?php

namespace App\Modules\System;

abstract class Query
{
	protected string $sql;
	protected array $params;

	public function getSql(): string
	{
		return $this->sql;
	}

	public function getParams(): array
	{
		return $this->params;
	}

	public function setParams(array $params): self
	{
		$this->params = $params;
		return $this;
	}

	public function execution(): DataBaseResult
	{
		$db = Container::getInstance()->get(MySqlDb::class);
		$this->generateSql();
		return $db->query($this->sql, $this->params);
	}

	protected function deletePlaceholder(string $placeholder): void
	{
		$this->sql = str_replace($placeholder, '', $this->sql);
	}

	abstract protected function generateSql(): self;
}