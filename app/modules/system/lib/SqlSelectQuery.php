<?php

namespace App\Modules\System;

class SqlSelectQuery extends SqlQuery
{
	protected array $sqlStringParameters;
	public function __construct(array $sqlStringParameters)
	{
		$this->sql = "SELECT ?COLUMNS? FROM ?TABLE? ?JOIN? ?WHERE? ?ORDER BY?;";
		$this->sqlStringParameters = $sqlStringParameters;
		$this->buildSqlString();
	}

	public function buildSqlString()
	{
		try {
			$this->addSelectColumnsToSqlString();
			$this->addSelectTableToSqlString();
			$this->addJoinsToSqlString();
			$this->addWhereStatementToSql();
			$this->addOrderByStatement();
			$this->sql = str_replace(", ?COLUMNS?", "", $this->sql);
		}catch (\Exception $exception)
		{
			echo $exception->getMessage();
		}
	}

	public function addSelectColumnsToSqlString()
	{
		$columns = "";
		if(!isset($this->sqlStringParameters['table']))
		{
			throw new \Exception('Please indicate table name.');
		}
		if(isset($this->sqlStringParameters['select']) && !empty($this->sqlStringParameters['select']))
		{
			$selects = $this->sqlStringParameters['select'];
			$table = $this->sqlStringParameters['table'];
			foreach ($selects as $as => $select)
			{
				if(is_string($as))
				{
					$columns .= "`{$table}`.`{$as}` as '{$select}', ";
				}else
				{
					$columns .= "`{$table}`.`{$select}`, ";
				}
			}
		}else
		{
			$columns .= "* ";
		}
		$this->sql = str_replace("?COLUMNS?", $columns . "?COLUMNS?", $this->sql);
	}

	public function addSelectTableToSqlString()
	{
		$this->sql = str_replace("?TABLE?", $this->sqlStringParameters['table'], $this->sql);
	}

	public function addJoinsToSqlString()
	{
		if(isset($this->sqlStringParameters['joins']) && !empty($this->sqlStringParameters['joins']))
		{
			$joins = $this->sqlStringParameters['joins'];
			foreach ($joins as $table => $join)
			{
				$this->addJoin($table, $join);
				$this->sqlStringParameters['select'] = $this->sqlStringParameters['joins'][$table]['columns'];
				$this->sqlStringParameters['table'] = $table;
				$this->addSelectColumnsToSqlString();
			}
		}
	}

	public function addJoin(string $table, array $join)
	{
		$sqlJoin = "JOIN {$table} ON ";
		$leftTable = $this->sqlStringParameters['table'];
		if(isset($join['on']) && !empty($join['on']))
		{
			foreach ($join['on'] as $columnLeft => $columnRight)
			{
				if(isset($join['table']))
				{
					$leftTable = $join['table'];
				}
				$sqlJoin .= "`{$leftTable}`.`{$columnLeft}` = `{$table}`.`{$columnRight}` ";
			}
		}
		$this->sql = str_replace('?JOIN?', $sqlJoin, $this->sql);
	}

	public function addWhereStatementToSql()
	{
		$where = "";
		if(isset($this->sqlStringParameters['where']) && !empty($this->sqlStringParameters['where']))
		{
			if(!strpos($this->sql, ' WHERE '))
			{
				$where = ' WHERE ';
			}
			$table = $this->sqlStringParameters['table'];
			if(isset($this->sqlStringParameters['where']['table']))
			{
				$table = $this->sqlStringParameters['where']['table'];
				unset($this->sqlStringParameters['where']['table']);
			}
			foreach ($this->sqlStringParameters['where'] as $column => $value)
			{
				$where .= "`{$table}`.`{$column}` {$value}";
			}
		}
		$this->sql = str_replace("?WHERE?", $where, $this->sql);
	}

	public function addOrderByStatement()
	{
		$orderBy = "";
		if(isset($this->sqlStringParameters['order']) && !empty($this->sqlStringParameters['order']))
		{
			$orderBy .= "ORDER BY ";
			$order = $this->sqlStringParameters['order'];
			$table = $this->sqlStringParameters['table'];
			if(isset($this->sqlStringParameters['order']['table']))
			{
				$table = $this->sqlStringParameters['order']['table'];
				unset($this->sqlStringParameters['order']['table']);
			}
			foreach ($order as $column => $value)
			{
				$orderBy .= "`{$table}`.`{$column}` '{$value}'";
			}
		}
		$this->sql = str_replace("?ORDER BY?", $orderBy, $this->sql);
	}
}