<?php

namespace App\Modules\System\File;

class File
{
	protected string $path;

	public function __construct(string $path)
	{
		$this->path = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
	}

	public function create()
	{
		if(!file_exists($this->path))
		{
			mkdir(dirname($this->path));
			$file = fopen($this->path, 'w+');
			fclose($file);
		}
	}

	public function write(string $content)
	{
		file_put_contents($this->path, $content, FILE_APPEND);
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getFileSize(): int
	{
		if(file_exists($this->path))
		{
			return filesize($this->path);
		}else
		{
			return 0;
		}
	}
}