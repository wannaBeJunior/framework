<?php

namespace App\Modules\System;

use Exception;

class UpdateQuery extends Query
{
	protected array $values = [];

	public function setValues(array $values): self
	{
		$this->values = $values;
		return $this;
	}

	protected function generateSql(): self
	{
		$this->sql = "UPDATE {TABLE} SET {VALUES}";
		try {
			$this->replaceTableName();
			$this->replaceValues();
		}catch (Exception $exception)
		{
			echo $exception->getMessage();
			die();
		}
		return $this;
	}

	protected function replaceValues(): self
	{
		$valuesPlaceholder = '{VALUES}';
		foreach($this->values as $field => $value)
		{
			$this->sql = str_replace($valuesPlaceholder, "`{$field}` = {$valuesPlaceholder} {$valuesPlaceholder}", $this->sql);
		}
		$this->deletePlaceholder($valuesPlaceholder);
		return $this;
	}
}