<?php

namespace App\Modules\System\DataBase\Queries;

use Exception;

class DeleteQuery extends Query
{
	protected function generateSql(): self
	{
		$this->sql = "DELETE FROM {TABLE} {WHERE};";
		try {
			$this->replaceTableName();
			$this->replaceWhere();
		}catch (Exception $exception)
		{
			echo $exception->getMessage();
			die();
		}
		return $this;
	}
}