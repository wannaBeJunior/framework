<?php

namespace App\Modules\System;

class View
{
	protected string $title;

	public function show(string $viewName, array $result = [])
	{
		$this->startBuffering();
		$this->showHeader();
		$this->showContent($viewName, $result);
		$this->showFooter();
		$html = $this->stopBuffering();
		$this->replacePlaceHolders($html);
		echo $html;
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

	public function replacePlaceHolders(&$html)
	{
		if(isset($this->title))
		{
			$html = str_replace('#TITLE#', $this->title, $html);
		}
	}

	public function setTitle(string $title)
	{
		$this->title = $title;
	}
}