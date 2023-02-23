<?php

namespace App\Modules\System\DataBase\Queries;

use Exception;

class InsertQuery extends Query
{
	private array $fields;
	private array $values;

	public function setFields(array $fields): self
	{
		$this->fields = $fields;
		return $this;
	}

	public function setValues(array $values): self
	{
		$this->values = $values;
		return $this;
	}

	protected function generateSql(): self
	{
		$this->sql = "INSERT INTO {TABLE} ({FIELDS}) VALUES ({VALUES});";
		try {
			$this->replaceTableName();
			$this->replaceFields();
			$this->replaceValues();
		}catch(Exception $exception)
		{
			echo $exception->getMessage();
			die();
		}
		return $this;
	}

	private function replaceFields()
	{
		if(!$this->fields)
		{
			throw new Exception('Add fields');
		}
		$fieldsPlaceholder = '{FIELDS}';
		for($i = 0; $i < count($this->fields); $i++)
		{
			$fieldDelimiter = '';
			if($i >= 0 && $i < count($this->fields) - 1)
			{
				$fieldDelimiter = ', ';
			}
			$field = $this->fields[$i];
			$this->sql = str_replace($fieldsPlaceholder, "`{$field}`{$fieldDelimiter} {$fieldsPlaceholder}", $this->sql);
		}
		$this->deletePlaceholder($fieldsPlaceholder);
	}

	private function replaceValues()
	{
		if(!$this->fields)
		{
			throw new Exception('Add values');
		}
		$valuesPlaceholder = '{VALUES}';
		for($i = 0; $i < count($this->values); $i++)
		{
			$fieldDelimiter = '';
			if($i >= 0 && $i < count($this->values) - 1)
			{
				$fieldDelimiter = ', ';
			}
			$value = $this->values[$i];
			$this->sql = str_replace($valuesPlaceholder, "{$value}{$fieldDelimiter} {$valuesPlaceholder}", $this->sql);
		}
		$this->deletePlaceholder($valuesPlaceholder);
	}
}