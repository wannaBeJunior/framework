<?php

namespace App\Modules\System\Response;

use App\Modules\System\Configuration\Configuration;
use App\Modules\System\Container\Container;

class HttpResponse extends Response
{

	public function send()
	{
		// TODO: Implement send() method.
	}

	static public function setHeaders()
	{
		/**
		 * @var Configuration $config
		 */
		$config = Container::getInstance()->get(Configuration::class);
		$corsPolicyParams = $config->get('CORS');

		if (isset($_SERVER['HTTP_ORIGIN']))
		{
			if(in_array($_SERVER['HTTP_ORIGIN'], $corsPolicyParams['allow_origins']))
			{
				header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
				header("Access-Control-Allow-Credentials: " . strval($corsPolicyParams['credentials']));
				header('Access-Control-Expose-Headers: ' . implode(', ', $corsPolicyParams['allow_headers']));
			}
		}

		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
		{
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
			{
				header("Access-Control-Allow-Methods: " . implode(', ', $corsPolicyParams['allow_methods']));
			}
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
			{
				header("Access-Control-Allow-Headers: " . implode(', ', $corsPolicyParams['allow_headers']));
			}
		}
	}
}