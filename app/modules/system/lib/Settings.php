<?php

namespace App\Modules\System;

class Settings
{
	protected DataBase $db;
	protected array $cache = [];
	public function __construct(DataBase $db)
	{
		$this->db = $db;
	}

	public function getBySettingName(string $name)
	{
		if(!isset($this->cache[$name]))
		{
			$sql = "SELECT `settings`.`name`,`setting_values`.`value`  FROM `megasport`.`settings` JOIN `setting_setting_values` ON `settings`.`id` = `setting_setting_values`.`setting` JOIN `setting_values` ON `setting_setting_values`.`value` = `setting_values`.`id` WHERE `selected` = 1 AND `settings`.`name` = :name;";
			$this->cache[$name] = $this->db->query($sql, [$name])->getResult()[0]['value'];
		}
		return $this->cache[$name];
	}
}