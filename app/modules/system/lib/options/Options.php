<?php

namespace App\Modules\System\Options;

use App\Modules\System\DataBase\Queries\SelectQuery;

class Options
{
	static protected array $cache;

	static public function getOption(string $optionName): array
	{
		if(isset(static::$cache[$optionName]))
		{
			return static::$cache[$optionName];
		}
		$option = (new SelectQuery())
			->setTableName('options')
			->setSelect(['*'])
			->setWhere([
				'condition' => 'name = :name',
			])
			->setParams([
				'name' => $optionName
			])
			->execution()
			->getResult();
		if($option)
		{
			$optionId = $option['id'];
			$optionValue = (new SelectQuery())
				->setTableName('option_values')
				->setSelect(['value'])
				->setWhere([
					'condition' => '`option` = ' . $optionId
				])
				->execution()
				->getResult();
			if($optionValue)
			{
				$optionResult = array_merge($option, $optionValue);
				static::$cache[$optionName] = $optionResult;
				return $optionResult;
			}
			$optionValue = (new SelectQuery())
				->setTableName('option_enums')
				->setSelect(['enum_value', 'selected', 'code'])
				->setWhere([
					'condition' => '`option` = ' . $optionId
				])
				->execution()
				->getResult();
			if($optionValue)
			{
				$option['values'] = $optionValue;
				static::$cache[$optionName] = $option;
				return $option;
			}
		}
		return $option;
	}
}