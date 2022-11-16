<?php

namespace App\Modules\System\Validator\Rules;

class Email implements RuleInterface
{
	private const EMAIL_PATTERN = '/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u';

	/**
	 * @param string $value
	 * @return bool
	 */
	public function check(string $value): bool
	{
		$emailRegex = new Regex(self::EMAIL_PATTERN);
		return $emailRegex->check($value);
	}
}