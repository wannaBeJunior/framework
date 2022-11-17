<?php

namespace App\Modules\System\Exceptions;

use Exception;
use Throwable;

class FileNotFoundException extends Exception implements Interfaces\OutableExceptionInterface
{
	/**
	 * @param string $message
	 */
	public function show(string $message): void
	{
		echo 'Что-то пошло не так. Но мы скоро всё починим.';
		// TODO: Implement show() method.
	}

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