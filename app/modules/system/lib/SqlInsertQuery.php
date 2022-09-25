<?php

namespace App\Modules\System;

class SqlInsertQuery extends SqlQuery
{
	public function __construct(array $sqlStringParameters)
	{
		parent::__construct($sqlStringParameters);
		$this->sql = "INSERT INTO ?TABLE? (?COLUMNS?) VALUES (?VALUES?)";
		$this->buildSqlString();
	}

	public function buildSqlString()
	{
		try {
			$this->addTableToSqlString();
			$this->addColumnsToSqlString();
			$this->addValuesToSqlString();
		}catch (\Exception $exception)
		{
			echo $exception->getMessage();
		}
	}

	public function addTableToSqlString()
	{
		if(isset($this->sqlStringParameters['table']))
		{
			$this->sql = str_replace('?TABLE?', "`{$this->sqlStringParameters['table']}`", $this->sql);
		}else
		{
			throw new \Exception('Please indicate table name.');
		}
	}

	public function addColumnsToSqlString()
	{
		$columns = "";
		if(isset($this->sqlStringParameters['fields']))
		{
			$fields = $this->sqlStringParameters['fields'];
			foreach ($fields as $field)
			{
				$columns .= "`{$field}`, ";
			}
			$this->sql = str_replace('?COLUMNS?', $columns, $this->sql);
			$this->sql = str_replace(', )', ")", $this->sql);
		}
	}

	public function addValuesToSqlString()
	{
		$valuesString = "";
		if(isset($this->sqlStringParameters['values']))
		{
			$values = $this->sqlStringParameters['values'];
			foreach ($values as $value)
			{
				$valuesString .= "'{$value}', ";
			}
			$this->sql = str_replace('?VALUES?', $valuesString, $this->sql);
			$this->sql = str_replace(', )', ")", $this->sql);
		}
	}
}