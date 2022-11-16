<?php

namespace App\Modules\System\Validator;

use App\Modules\System\Validator\Rules\RuleInterface;

class Validator
{
	/**
	 * @param array $values
	 * @param array $rules
	 * @return array
	 */
	static public function run(array $values, array $rules): array
	{
		$result = [];
		foreach($values as $name => $value)
		{
			if(isset($rules[$name]))
			{
				foreach($rules[$name] as $rule)
				{
					$result[$name] = true;
					if($rule instanceof RuleInterface)
					{
						if(!$rule->check($value))
						{
							$result[$name] = false;
							break;
						}
					}
				}
			}
		}
	    return $result;
	}
}