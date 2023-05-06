<?php

namespace App\Modules\System\View;

class View
{
	protected string $title;
	protected array $placeholders = [];

	public function show(string $viewName, array $result = [])
	{
		$this->startBuffering();
		$this->showHeader($result);
		$this->showContent($viewName, $result);
		$this->showFooter($result);
		$html = $this->stopBuffering();
		$this->replacePlaceHolders($html);
		echo $html;
	}

	public function startBuffering()
	{
		ob_start();
	}

	public function showHeader(array $result)
	{
		$headerFilePath = $_SERVER['DOCUMENT_ROOT'] . '/app/admin/views/templates/header.php';
		if(file_exists($headerFilePath))
		{
			require_once $headerFilePath;
		}
	}

	public function showContent(string $viewName, array $result)
	{
		require_once $_SERVER['DOCUMENT_ROOT'] . '/app/admin/views/' . $viewName . '.php';
	}

	public function showFooter(array $result)
	{
		$footerFilePath = $_SERVER['DOCUMENT_ROOT'] . '/app/admin/views/templates/footer.php';
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
		if($this->placeholders)
		{
			foreach($this->placeholders as $placeholder => $content)
			{
				$html = str_replace("#{$placeholder}#", $content, $html);
			}
		}
	}

	public function setTitle(string $title)
	{
		$this->title = $title;
	}

	public function setPlaceholder(string $key, string $content)
	{
		$this->placeholders[$key] = $content;
	}
}