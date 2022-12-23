<?php

namespace App\Modules\System\Logger;

class Logger
{
	protected const NOTICE_LOGFILE_PATH = '/logs/notices/';
	protected const WARNING_LOGFILE_PATH = '/logs/warnings/';
	protected const ERROR_LOGFILE_PATH = '/logs/errors/';

	public function __construct()
	{
		//TODO: реализовать класс File
	}

	/**
	 * Запишет в лог файл уведомление о какой-либо лёгкой ошибке которая не влияет на работу системы.
	 * @param string $message
	 * @param string $file
	 */
	public function notice(string $message, string $file = 'sys.log'): void
	{
		$message = '['. date('Y-m-j G:i:s') . ' NOTICE at ' . self::getStackTraceInfo() . '] ' . $message . PHP_EOL;
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . self::NOTICE_LOGFILE_PATH . $file, $message, FILE_APPEND);
	}

	/**
	 * Запишет в лог файл предупреждение на которые стоит обращать внимание.
	 * @param string $message
	 * @param string $file
	 */
	public function warning(string $message, string $file = 'sys.log'): void
	{
		$message = '['. date('Y-m-j G:i:s') . ' WARNING at ' . self::getStackTraceInfo() . '] ' . $message . PHP_EOL;
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . self::WARNING_LOGFILE_PATH . $file, $message, FILE_APPEND);
	}

	/**
	 * Запишет в лог файл ошибку после которой система не сможет продолжать работать
	 * @param string $message
	 * @param string $file
	 */
	public function error(string $message, string $file = 'sys.log'): void
	{
		$message = '['. date('Y-m-j G:i:s') . ' ERROR at ' . self::getStackTraceInfo() . '] ' . $message . PHP_EOL;
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . self::ERROR_LOGFILE_PATH . $file, $message, FILE_APPEND);
	}

	static public function getStackTraceInfo(): string
	{
		$stackTrace = debug_backtrace();
		return "{$stackTrace[1]['file']}:{$stackTrace[1]['line']}";
	}
}