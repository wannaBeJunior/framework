<?php

namespace App\Modules\System\Options;

use App\Modules\System\DataBase\Queries\SelectQuery;

class Options
{
	static public function getOption(string $optionName): array
	{
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
				return array_merge($option, $optionValue);
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
				return $option;
			}
		}
		return $option;
	}
}