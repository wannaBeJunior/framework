<?php

namespace App\Modules\System\Validator\Rules;

class Regex implements RuleInterface
{
	private string $pattern;

	public function __construct(string $pattern)
	{
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