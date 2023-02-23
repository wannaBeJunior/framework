<?php

namespace App\Modules\System\DataBase\Queries;

use App\Modules\System\Container\Container;
use App\Modules\System\DataBase\DataBaseResult;
use App\Modules\System\DataBase\MySqlDb;

abstract class Query
{
	protected string $sql;
	protected array $params = [];
	protected string $tableName = '';
	private array $where = [];

	public function getSql(): string
	{
		return isset($this->sql)?:'';
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

	public function setWhere(array $where): self
	{
		$this->where[] = $where;
		return $this;
	}

	protected function replaceWhere(): void
	{
		$wherePlaceholder = '{WHERE}';
		if($this->where)
		{
			for ($i = 0; $i < count($this->where); $i++)
			{
				if($i == 0)
				{
					$this->where[$i]['logic'] = 'WHERE ';
				}
				$logic = strtoupper($this->where[$i]['logic']);
				$condition = $this->where[$i]['condition'];
				$this->sql = str_replace($wherePlaceholder, "{$logic} {$condition} {$wherePlaceholder}", $this->sql);
			}
		}
		$this->deletePlaceholder($wherePlaceholder);
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