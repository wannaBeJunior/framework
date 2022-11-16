<?php

namespace App\Modules\System\Validator\Rules;

class Password implements RuleInterface
{
	private const PASSWORD_PATTERN = '/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]+$/u';

	/**
	 * @param string $value
	 * @return bool
	 */
	public function check(string $value): bool
	{
		$passwordRegex = new Regex(self::PASSWORD_PATTERN);
		return $passwordRegex->check($value);
	}
}