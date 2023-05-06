<?php

namespace App\Modules\System\User;

use App\Modules\System\DataBase\DataBaseResult;
use App\Modules\System\DataBase\Queries\SelectQuery;
use App\Modules\System\DataBase\Queries\UpdateQuery;
use App\Modules\System\Logger\Logger;
use App\Modules\System\Options\Options;
use App\Modules\System\Request\Request;
use App\Modules\System\Session\Session;

class Authentication
{
	protected Logger $logger;
	protected array $errors;
	protected Request $request;

	public function getErrors(): array
	{
		return $this->errors;
	}

	public function isSuccess(): bool
	{
		return !(bool) count($this->errors);
	}

	public function __construct(Request $request)
	{
		$this->logger = new Logger();
		$this->request = $request;
	}

	public function run()
	{
		$data = $this->setDataKeysToUpperCase($this->request->getPostParameters());

		if(!$data)
		{
			$this->logger->warning("В метод аутентификации пользователя передан пустой массив полей.");
			return;
		}

		$this->checkRequiredFields();
		if(isset($this->errors) && $this->errors)
		{
			return;
		}

		$user = $this->getUser();
		if(!$user->isSuccess())
		{
			$this->setErrors('', 'Что-то пошло не так');
			return;
		}
		if(!$user->getRowsCount())
		{
			$this->setErrors('', 'Такой пользователь не найден');
			return;
		}
		$userData = $user->getResult();
		if(!password_verify($data['PASSWORD'], $userData['password']))
		{
			$this->setErrors('PASSWORD', 'Введен неверный пароль');
			return;
		}
		$this->saveLoginTime($userData['id']);
		Session::set('USER', [
			'ID' => $userData['id'],
			'ROLE' => $userData['code']
		]);
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
	 * ПОиск пользователя в БД по его данным
	 * @return DataBaseResult
	 */
	protected function getUser(): DataBaseResult
	{
		$data = $this->setDataKeysToUpperCase($this->request->getPostParameters());
		$requiredFields = Options::getOption('required_user_fields');
		$fields = [];
		$userSelect = (new SelectQuery)
			->setTableName('users')
			->setSelect(['users.*', 'access_levels.name'])
			->setJoin([
				'type' => 'inner',
				'ref_table' => 'access_levels',
				'on' => 'this.access_level = ref.id'
			]);
		foreach ($requiredFields['values'] as $requiredField)
		{
			$fields[mb_strtolower($requiredField['code'])] = $data[$requiredField['code']];
			$userSelect->setWhere([
				'condition' => mb_strtolower($requiredField['code']) . ' = :' . mb_strtolower($requiredField['code']),
				'logic' => 'AND'
			]);
		}
		return $userSelect->setParams($fields)->execution();
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

	/**
	 * Сохраняет время последнего логина пользователя
	 * @param int $userId
	 */
	protected function saveLoginTime(int $userId)
	{
		(new UpdateQuery())
			->setTableName('users')
			->setFields(['last_login_date'])
			->setWhere([
				'condition' => 'id = ' . $userId
			])
			->setParams([
				'last_login_date' => date('Y-m-d H:i:s')
			])
			->execution();
	}
}