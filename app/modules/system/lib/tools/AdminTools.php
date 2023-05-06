<?php

namespace App\Modules\System\Tools;

class AdminTools
{
	static public function getModules()
	{
		$modules = scandir($_SERVER['DOCUMENT_ROOT'] . '/app/modules');
		$result = [];
		foreach ($modules as $module)
		{
			if($module == '.' || $module == '..')
			{
				continue;
			}
			$descriptionFilePath = $_SERVER['DOCUMENT_ROOT'] . '/app/modules/' . $module . '/description.php';
			if(file_exists($descriptionFilePath))
			{
				$result[$module] = include $descriptionFilePath;
				continue;
			}
			$result[$module] = [
				'name' => 'Модуль ' . $module,
				'description' => 'Модуль ' . $module
			];
		}
		return $result;
	}
}