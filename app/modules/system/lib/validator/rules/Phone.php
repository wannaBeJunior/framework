<?php

namespace App\Modules\System\Validator\Rules;

class Phone implements RuleInterface
{
	private const PHONE_PATTERN = '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,9}$/u';

	/**
	 * @param string $value
	 * @return bool
	 */
	public function check(string $value): bool
	{
		$phoneRegex = new Regex(self::PHONE_PATTERN);
		return $phoneRegex->check($value);
	}
}