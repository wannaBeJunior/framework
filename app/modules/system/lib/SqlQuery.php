<?php

namespace App\Modules\System;

abstract class SqlQuery
{
	protected string $sql;
	protected array $sqlStringParameters;

	public function __construct(array $sqlStringParameters)
	{
		$this->sqlStringParameters = $sqlStringParameters;
	}

	public function getSql() : string
	{
		return $this->sql;
	}
}