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
	protected string $error;
	protected string $errorCode;
	protected bool $success = true;

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
		$this->success = true;
		$this->rowsCount = $statement->rowCount();
		if($this->rowsCount > 1)
		{
			$this->result = $statement->fetchAll();
		}elseif($this->rowsCount == 1)
		{
			$this->result = $statement->fetch();
		}else
		{
			$this->result = [];
		}
		$this->completionTime = $this->endTime - $this->startTime;
		$this->lastInsertedId = $pdo->lastInsertId();
		$this->sql = $sql;
	}

	public function setErrorResult(\PDOException $exception)
	{
		$this->success = false;
		$this->errorCode = $exception->getCode();
		$this->error = $exception->getMessage();
	}

	public function getRowsCount(): int
	{
		return $this->rowsCount ?? 0;
	}

	public function getCompletionTime(): int
	{
		return $this->completionTime ?? 0;
	}

	public function getResult(): array
	{
		return $this->result ?? [];
	}

	public function getError(): string
	{
		return $this->error ?? '';
	}

	public function getErrorCode(): string
	{
		return $this->errorCode ?? '';
	}

	public function getLastInsertedId(): int
	{
		return $this->lastInsertedId ?? 0;
	}

	public function getSql(): string
	{
		return $this->sql ?? '';
	}

	public function isSuccess(): bool
	{
		return $this->success;
	}
}