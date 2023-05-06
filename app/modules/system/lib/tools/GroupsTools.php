<?php

namespace App\Modules\System\Tools;

use App\Modules\System\DataBase\Queries\SelectQuery;

class GroupsTools
{
	static public function getUserGroups(int $userId): array
	{
		if(!$userId)
		{
			return [];
		}

		$groups = (new SelectQuery())
			->setTableName('groups_users')
			->setSelect(['`group`'])
			->setWhere([
				'condition' => 'user = ' . $userId
			])
			->execution();
		if($groups->isSuccess())
		{
			return $groups->getResult();
		}
		return [];
	}
}