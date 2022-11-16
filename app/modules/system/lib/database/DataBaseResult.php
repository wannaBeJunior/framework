<?php

namespace App\Modules\System\DataBase;

use PDO;
use PDOStatement;

class DataBaseResult
{
	protected int $rowsCount;
	protected int $completionTime;
	protected array $result = [];
	protected int $lastInsertedId;
	protected int $startTime;
	protected int $endTime;

	public function startTimer(): void
	{
		$this->startTime = microtime(true);
	}

	public function stopTimer(): void
	{
		$this->endTime = microtime(true);
	}

	public function setResult(PDOStatement $statement, PDO $pdo): void
	{
		$this->rowsCount = $statement->rowCount();
		if($this->rowsCount > 1)
		{
			$this->result = $statement->fetchAll();
		}elseif($this->rowsCount == 1)
		{
			$this->result = $statement->fetch();
		}
		$this->completionTime = $this->endTime - $this->startTime;
		$this->lastInsertedId = $pdo->lastInsertId();
	}

	public function getRowsCount(): int
	{
		return $this->rowsCount;
	}

	public function getCompletionTime(): int
	{
		return $this->completionTime;
	}

	public function getResult(): array
	{
		return $this->result;
	}

	public function getLastInsertedId(): int
	{
		return $this->lastInsertedId;
	}
}