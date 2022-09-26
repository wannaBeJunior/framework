<?php

namespace App\Modules\System;

class View
{
	public function show(string $viewName, array $result = [])
	{
		$this->startBuffering();
		$this->showHeader();
		$this->showContent($viewName, $result);
		$this->showFooter();
		echo $this->stopBuffering();
	}

	public function startBuffering()
	{
		ob_start();
	}

	public function showHeader()
	{
		$headerFilePath = $_SERVER['DOCUMENT_ROOT'] . '/app/views/templates/header.php';
		if(file_exists($headerFilePath))
		{
			require_once $headerFilePath;
		}
	}

	public function showContent(string $viewName, array $result)
	{
		require_once $_SERVER['DOCUMENT_ROOT'] . '/app/views/' . $viewName . '.php';
	}

	public function showFooter()
	{
		$footerFilePath = $_SERVER['DOCUMENT_ROOT'] . '/app/views/templates/footer.php';
		if(file_exists($footerFilePath))
		{
			require_once $footerFilePath;
		}
	}

	public function stopBuffering()
	{
		return ob_get_clean();
	}
}