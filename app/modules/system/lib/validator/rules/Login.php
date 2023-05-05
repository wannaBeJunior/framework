<?php

namespace App\Modules\System\Validator\Rules;

class Login implements RuleInterface
{
	private const LOGIN_PATTERN = '/^[a-zA-Z0-9!@#$%^&*()]*$/u';
	/**
	 * @inheritDoc
	 */
	public function check(string $value): bool
	{
		$loginRegex = new Regex(self::LOGIN_PATTERN);
		return $loginRegex->check($value);
	}
}