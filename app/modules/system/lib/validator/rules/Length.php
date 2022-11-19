<?php

namespace App\Modules\System\Validator\Rules;

use InvalidArgumentException;

class Length implements RuleInterface
{
	private int $min;
	private int $max;

	/**
	 * @param int $min
	 * @param int $max
	 * @throws InvalidArgumentException
	 */
	public function __construct(int $min, int $max)
	{
		if($max < $min)
		{
			throw new InvalidArgumentException('Argument max cannot be less then min');
		}
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