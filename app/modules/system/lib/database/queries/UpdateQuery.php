<?php

namespace App\Modules\System\DataBase\Queries;

use Exception;

class UpdateQuery extends Query
{
	protected array $fields = [];

	public function setFields(array $fields): self
	{
		$this->fields = $fields;
		return $this;
	}

	protected function generateSql(): self
	{
		$this->sql = "UPDATE {TABLE} SET {VALUES} {WHERE}";
		try {
			$this->replaceTableName();
			$this->replaceFields();
			$this->replaceWhere();
		}catch (Exception $exception)
		{
			echo $exception->getMessage();
			die();
		}
		return $this;
	}

	protected function replaceFields(): self
	{
		$valuesPlaceholder = '{VALUES}';
		foreach($this->fields as $field)
		{
			$this->sql = str_replace($valuesPlaceholder, "`{$field}` = :{$field} {$valuesPlaceholder} {$valuesPlaceholder}", $this->sql);
		}
		$this->deletePlaceholder($valuesPlaceholder);
		return $this;
	}
}