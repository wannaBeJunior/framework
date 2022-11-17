<?php

namespace App\Modules\System\Exceptions;

use InvalidArgumentException as BaseException;
use Throwable;

class InvalidArgumentException extends BaseException implements Interfaces\LoggedExceptionInterface
{
	/**
	 * @param string $message
	 * @param int $code
	 */
	public function logWrite(string $message, int $code): void
	{
		echo 'logging...';
		// TODO: Implement logWrite() method.
	}
}