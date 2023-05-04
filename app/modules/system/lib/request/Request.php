<?php

namespace App\Modules\System\Request;

class Request
{
	protected array $post = [];
	protected array $get = [];

	public function __construct()
	{
		$this->post = $this->specialCharsConverting($_POST);
		$this->get = $this->specialCharsConverting($_GET);
	}

	public function getPostParameter(string $key, $default = null)
	{
		return $this->post[$key] ?? $default;
	}

	public function getPostParameters($default = null): array
	{
		return $this->post ?? $default;
	}

	public function getQueryParameter(string $key, $default = null)
	{
		return $this->get[$key] ?? $default;
	}

	public function getQueryParameters($default = null): array
	{
		return $this->get ?? $default;
	}

	/**
	 * @var array|string $params
	 */
	protected function specialCharsConverting($params)
	{
		if(is_array($params))
		{
			foreach ($params as &$param)
			{
				if(is_string($param))
				{
					$param = strip_tags($param);
				}elseif (is_array($param))
				{
					$param = $this->specialCharsConverting($param);
				}
			}
		}
		return $params;
	}
}