<?php

namespace App\Modules\System\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
	public function logWrite(string $message, int $code): void
	{
		echo 'logging...';
	}
}