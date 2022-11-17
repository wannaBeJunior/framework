<?php

namespace App\Modules\System\Exceptions;

use Exception;
use Throwable;

class RouteNotFoundException extends Exception implements Interfaces\OutableExceptionInterface
{
	public function show(string $message): void
	{
		echo 'Что-то пошло не так но мы скоро починим';
		// TODO: Implement show() method.
	}

	public function logWrite(string $message, int $code): void
	{
		echo 'logging...';
		// TODO: Implement logWrite() method.
	}
}