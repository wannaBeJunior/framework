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
		$this->userData = $this->setDataKeysToUpperCase($this->request->getPostParameters());

		if(!$this->userData)
		{
			$this->logger->warning("В метод аутентификации пользователя передан пустой массив полей.");
			return;
		}

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
		if(!password_verify($this->userData['PASSWORD'], $userData['password']))
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
	 * ПОиск пользователя в БД по его email
	 * @return DataBaseResult
	 */
	protected function getUser(): DataBaseResult
	{
		return (new SelectQuery)
			->setTableName('users')
			->setSelect(['*'])
			->setWhere([
				'condition' => 'email = :email'
			])
			->setParams([
				'email' => $this->userData['EMAIL']
			])
			->execution();
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