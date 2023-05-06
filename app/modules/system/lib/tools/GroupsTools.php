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

	/**
	 * Проверяет, имеют ли группы доступ к тому или иному модулю/сущности
	 * @param string $module
	 * @param int|array $groups
	 * @return bool
	 */
	static public function checkAccessRightsByGroupIds(string $module, $groups): bool
	{
		if(!is_array($groups))
		{
			$groups = [$groups];
		}

		$accessRights = (new SelectQuery())
			->setTableName('access_rights_groups')
			->setJoin([
				'type' => 'inner',
				'ref_table' => 'access_rights',
				'on' => 'this.access_right = ref.id'
			])
			->setSelect(['*']);
		$params = [];
		for($i = 0; $i < count($groups); $i++)
		{
			$accessRights->setWhere([
				'condition' => '`group` = :group' . $i,
				'logic' => 'OR'
			]);
			$params['group' . $i] = $groups[$i];
		}
		$accessRightsResult = $accessRights->setParams($params)
			->execution();
		if($accessRightsResult->isSuccess())
		{
			$result = [];
			$accessRights = $accessRightsResult->getResult();
			foreach($accessRights as $accessRight)
			{
				if($accessRight['module'] == $module && !isset($result[$accessRight['group']]))
				{
					return true;
				}
			}
			return false;
		}
		return false;
	}
}