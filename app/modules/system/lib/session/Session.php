<?php

namespace App\Modules\System\Session;

class Session
{
	static public function start() : void
	{
		if(!self::isStarted())
			session_start();
	}

	static public function isStarted() : bool
	{
		return session_status() === PHP_SESSION_ACTIVE;
	}

	static public function clear() : void
	{
		if(self::isStarted())
			session_unset();
	}

	static public function save() : void
	{
		if(self::isStarted())
			session_write_close();
	}

	static public function abort() : void
	{
		if(self::isStarted())
			session_abort();
	}

	static public function set(string $key, $value) : void
	{
		$_SESSION[$key] = $value;
	}

	static public function get(string $key)
	{
		if(static::has($key))
		{
			return $_SESSION[$key];
		}
		return null;
	}

	static public function has(string $key)
	{
		return isset($_SESSION[$key]);
	}
}