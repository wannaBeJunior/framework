<?php

namespace App\Modules\System;

class HttpContext
{
	protected array $httpPostOptions;
	protected array $httpGetOptions;

	public function __construct()
	{
		$this->setHttpGetOptions();
		$this->setHttpPostOptions();
		//TODO добавить методы для работы с url
	}

	public function setHttpPostOptions()
	{
		foreach ($_POST as $name => $value)
		{
			$this->httpPostOptions[$name] = htmlspecialchars($value);
		}
	}

	public function setHttpGetOptions()
	{
		foreach ($_GET as $name => $value)
		{
			$this->httpGetOptions[$name] = htmlspecialchars($value);
		}
	}

	public function getPostOption(string $optionName) : string
	{
		$result = "";
		if(isset($this->httpPostOptions[$optionName]))
		{
			$result = $this->httpPostOptions[$optionName];
		}
		return $result;
	}

	public function getGetOption(string $optionName) : string
	{
		$result = "";
		if(isset($this->httpGetOptions[$optionName]))
		{
			$result = $this->httpGetOptions[$optionName];
		}
		return $result;
	}
}