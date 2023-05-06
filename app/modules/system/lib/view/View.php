<?php

namespace App\Modules\System\View;

class View
{
	protected string $title;
	protected array $placeholders = [];
	protected array $css = [];
	protected array $js = [];

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
		if($this->css)
		{
			foreach($this->css as $css)
			{
				$html = str_replace("</head>", "<link href='{$css}?" . time() . "' rel='stylesheet'></head>", $html);
			}
		}
		if($this->js)
		{
			foreach($this->js as $js)
			{
				$html = str_replace("</body>", "<script src='{$js}?" . time() . "'></script></body>", $html);
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

	protected function includeCss(string $path)
	{
		$this->css[] = $path;
	}

	protected function includeJs(string $path)
	{
		$this->js[] = $path;
	}
}