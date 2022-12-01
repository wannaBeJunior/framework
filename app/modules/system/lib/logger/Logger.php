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
		$message = '['. date('Y-m-j G:i:s') . ' NOTICE]' . $message;
		file_put_contents(self::NOTICE_LOGFILE_PATH . $file, $message);
	}

	/**
	 * Запишет в лог файл предупреждение на которые стоит обращать внимание.
	 * @param string $message
	 * @param string $file
	 */
	public function warning(string $message, string $file = 'sys.log'): void
	{
		$message = '['. date('Y-m-j G:i:s') . ' WARNING]' . $message;
		file_put_contents(self::WARNING_LOGFILE_PATH . $file, $message);
	}

	/**
	 * Запишет в лог файл ошибку после которой система не сможет продолжать работать
	 * @param string $message
	 * @param string $file
	 */
	public function error(string $message, string $file = 'sys.log'): void
	{
		$message = '['. date('Y-m-j G:i:s') . ' ERROR]' . $message;
		file_put_contents(self::ERROR_LOGFILE_PATH . $file, $message);
	}
}