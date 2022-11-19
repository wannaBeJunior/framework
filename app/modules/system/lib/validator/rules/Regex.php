<?php

namespace App\Modules\System\Validator\Rules;

use InvalidArgumentException;

class Regex implements RuleInterface
{
	private string $pattern;

	/**
	 * @param string $pattern
	 * @throws InvalidArgumentException
	 */
	public function __construct(string $pattern)
	{
		if(!$pattern || mb_substr($pattern, 0, 1) != '/')
		{
			throw new InvalidArgumentException('Invalid pattern ' . $pattern);
		}
		$this->pattern = $pattern;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function check(string $value): bool
	{
		return preg_match($this->pattern, $value);
	}
}