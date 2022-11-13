<?php

namespace App\Modules\System;

abstract class Query
{
	protected string $sql;
	protected array $params;
	protected string $tableName = '';

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

	public function setTableName(string $tableName): self
	{
		$this->tableName = $tableName;
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

	protected function replaceTableName(): void
	{
		if(!$this->tableName)
		{
			throw new \Exception('Add table name to query');
		}
		$tableNamePlaceholder = '{TABLE}';
		$this->sql = str_replace($tableNamePlaceholder, "`{$this->tableName}` ", $this->sql);
		$this->deletePlaceholder($tableNamePlaceholder);
	}

	abstract protected function generateSql(): self;
}