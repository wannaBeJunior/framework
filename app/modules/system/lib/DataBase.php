<?php

namespace App\Modules\System;

abstract class DataBase
{
	protected \PDO $pdo;

	final public function sqlExecution(string $sql, array $arParams = []) : array
	{
		$status = true;
		preg_match_all('/:([A-Za-z0-9_]+)/', $sql, $matches);
		$values = $matches[1];
		if(count($values) != count($arParams))
		{
			return [
				'status' => false,
				'description' => 'Check your param array'
			];
		}
		$stmt = $this->pdo->prepare($sql);
		$requestType = self::getRequestType($sql);
		$response = $stmt->execute($arParams);
		if($response)
		{
			if($requestType == 'SELECT')
			{
				return [
					'status' => true,
					'data' => $stmt->fetchAll()
				];
			}elseif($requestType == 'INSERT')
			{
				return [
					'status' => true,
					'id' => $this->pdo->lastInsertId()
				];
			}else
			{
				return [
					'status' => true
				];
			}
		}
		return [
			'status' => false,
			'description' => 'PDO error'
		];
	}

	final static public function getRequestType(string $sql)
	{
		preg_match('/^([A-Za-z]+)/', $sql, $matches);
		if(count($matches) > 0)
		{
			return $matches[0];
		}
		return false;
	}
}