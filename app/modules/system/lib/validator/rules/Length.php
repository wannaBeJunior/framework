<?php

namespace App\Modules\System\Validator\Rules;

class Length implements RuleInterface
{
	private int $min;
	private int $max;

	public function __construct(int $min, int $max)
	{
		$this->min = $min;
		$this->max = $max;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function check(string $value): bool
	{
		$valueLength = mb_strlen($value);
		if($valueLength <= $this->max && $valueLength >= $this->min)
			return true;
		return false;
	}
}