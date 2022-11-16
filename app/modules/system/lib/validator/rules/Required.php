<?php

namespace App\Modules\System\Validator\Rules;

class Required implements RuleInterface
{
	/**
	 * @param string $value
	 * @return bool
	 */
	public function check(string $value): bool
	{
		$valueLength = mb_strlen($value);
		if($valueLength >= 1)
			return true;
		return false;
	}
}