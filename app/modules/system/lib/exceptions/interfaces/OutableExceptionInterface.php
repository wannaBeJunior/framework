<?php

namespace App\Modules\System\Exceptions\Interfaces;

interface OutableExceptionInterface
{
	public function show(string $message): void;
	public function logWrite(string $message, int $code): void;
}