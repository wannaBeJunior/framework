<?php

namespace App\Modules\System\DataBase;

use PDO;
use PDOStatement;

class DataBaseResult
{
	protected int $rowsCount;
	protected int $completionTime;
	protected $result;
	protected int $lastInsertedId;
	protected int $startTime;
	protected int $endTime;
	protected string $sql;

	public function startTimer(): void
	{
		$this->startTime = microtime(true);
	}

	public function stopTimer(): void
	{
		$this->endTime = microtime(true);
	}

	public function setResult(PDOStatement $statement, PDO $pdo, string $sql): void
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
		$this->sql = $sql;
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

	public function getSql(): string
	{
		return $this->sql;
	}
}