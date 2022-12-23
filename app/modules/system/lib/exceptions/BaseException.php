<?php

namespace App\Modules\System\Exceptions;

use App\Modules\System\Container\Container;
use App\Modules\System\Logger\Logger;
use Exception;
use Throwable;

abstract class BaseException extends Exception
{
	private Logger $logger;

	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		$this->logger = Container::getInstance()->get(Logger::class);
		parent ::__construct($message, $code, $previous);
	}

	public function getLogger(): Logger
	{
		return $this->logger;
	}
}