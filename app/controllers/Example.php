<?php
namespace App\Controllers;
use App\Modules\System\ControllerInterface;

class Example implements ControllerInterface
{
	public function example()
	{
		echo 'Example';
	}
}