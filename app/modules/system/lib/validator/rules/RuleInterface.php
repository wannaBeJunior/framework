<?php

namespace App\Modules\System\Validator\Rules;

interface RuleInterface
{
	/**
	 * @param string $value
	 * @return bool
	 */
	public function check(string $value): bool;
}