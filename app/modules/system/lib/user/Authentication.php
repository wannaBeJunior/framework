<?php

namespace App\Modules\System\User;

use App\Modules\System\DataBase\DataBaseResult;
use App\Modules\System\DataBase\Queries\SelectQuery;
use App\Modules\System\DataBase\Queries\UpdateQuery;
use App\Modules\System\Logger\Logger;
use App\Modules\System\Options\Options;
use App\Modules\System\Request\Request;
use App\Modules\System\Session\Session;
use App\Modules\System\Tools\GroupsTools;

class Authentication extends BaseUserAction
{
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
		$userGroups = GroupsTools::getUserGroups($userData['id']);
		Session::set('USER', [
			'ID' => $userData['id'],
			'GROUPS' => array_column($userGroups, 'group')
		]);
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
			->setSelect(['*']);
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