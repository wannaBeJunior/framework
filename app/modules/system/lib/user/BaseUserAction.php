<?php

namespace App\Modules\System\User;

use App\Modules\System\Logger\Logger;
use App\Modules\System\Options\Options;
use App\Modules\System\Request\Request;

abstract class BaseUserAction
{
	protected Logger $logger;
	protected array $errors = [];
	protected Request $request;
	protected array $userData;

	abstract protected function run();

	public function getErrors(): array
	{
		return $this->errors;
	}

	public function isSuccess(): bool
	{
		return !(bool) count($this->errors);
	}

	/**
	 * Приводит ключи элементов массива к верхнему регистру
	 * @param array $data
	 * @return array
	 */
	protected function setDataKeysToUpperCase(array $data): array
	{
		$newData = [];
		foreach ($data as $key => $value)
		{
			if(is_array($value))
			{
				$newData[] = $this->setDataKeysToUpperCase($value);
				continue;
			}
			$newData[mb_strtoupper($key)] = $value;
		}
		return $newData;
	}

	/**
	 * @param string $field
	 * @param string $message
	 */
	protected function setErrors(string $field, string $message): void
	{
		$this->errors[] = [
			'field' => $field,
			'message' => $message
		];
	}
}