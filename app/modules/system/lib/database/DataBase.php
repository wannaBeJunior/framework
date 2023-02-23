<?php

namespace App\Modules\System\DataBase;

use PDO;

abstract class DataBase
{
	protected PDO $pdo;

	final public function query(string $sql, array $params = []): DataBaseResult
	{
		try {
			$dataBaseResult = new DataBaseResult;
			$dataBaseResult->startTimer();
			if(!self::checkSqlStringPlaceholdersEquality($sql, $params))
			{
				throw new \PDOException('Placeholders numbers does not equal param array count');
			}
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($params);
			$dataBaseResult->stopTimer();
			$dataBaseResult->setResult($stmt, $this->pdo, $sql);
			return $dataBaseResult;
		}catch (\PDOException $exception)
		{
			echo $exception->getMessage();
			die();
		}
	}

	final static public function checkSqlStringPlaceholdersEquality(string $sql, array $params): bool
	{
		preg_match_all('/:([A-Za-z0-9_]+)/', $sql, $matches);
		return count($matches[1]) == count($params);
	}
}