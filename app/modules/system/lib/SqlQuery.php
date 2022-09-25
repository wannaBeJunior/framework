<?php

namespace App\Modules\System;

abstract class SqlQuery
{
	protected string $sql;

	public function getSql() : string
	{
		return $this->sql;
	}
}