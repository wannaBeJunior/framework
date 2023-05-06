<?php

namespace App\Modules\System\User;

use App\Modules\System\Logger\Logger;
use App\Modules\System\Options\Options;
use App\Modules\System\Request\Request;

abstract class BaseUserAction
{
	protected Logger $logger;
	protected array $errors;
	protected Request $request;

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
	 * Проверяет, что все обязательные поля заполнены
	 */
	protected function checkRequiredFields(): void
	{
		$data = $this->setDataKeysToUpperCase($this->request->getPostParameters());
		$requiredFields = Options::getOption('required_user_fields');
		$requiredFields['values'][] = [
			'code' => 'PASSWORD'
		];
		foreach ($requiredFields['values'] as $requiredField)
		{
			if(!in_array($requiredField['code'], array_keys($data)))
			{
				$this->setErrors($requiredField['code'], 'Не было заполнено обязательное поле');
			}
		}
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