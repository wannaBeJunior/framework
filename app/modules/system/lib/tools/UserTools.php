<?php

namespace App\Modules\System\Tools;

use App\Modules\System\Session\Session;

class UserTools
{
	/**
	 * Возвращает true в случае если текущий пользователь аутентифицирован,
	 * false в противном случае
	 * @return bool
	 */
	static public function isAuthenticated(): bool
	{
		return Session::has('USER');
	}

	/**
	 * Возвращает id групп текущего пользователя
	 * @return array
	 */
	static public function getCurrentUserGroups(): array
	{
		if(static::isAuthenticated())
		{
			return Session::get('USER')['GROUPS'];
		}
		return [];
	}
}