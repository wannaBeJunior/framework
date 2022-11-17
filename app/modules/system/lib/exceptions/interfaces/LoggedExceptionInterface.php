<?php

namespace App\Modules\System\Exceptions\Interfaces;

interface LoggedExceptionInterface
{
	public function logWrite(string $message, int $code): void;
}