<?php
namespace App\Controllers;

use App\Modules\System\Container\Container;
use App\Modules\System\Controller\ControllerInterface;
use App\Modules\System\Request\Request;
use App\Modules\System\User\Authentication;

class Example implements ControllerInterface
{
	public function example(array $params)
	{
		$_POST['email'] = 'admin@hermes.com';
		$_POST['login'] = 'admin';
		$_POST['phone'] = '+79874188056';
		$_POST['password'] = 'Ruzvelt1337!';
		$request = Container::getInstance()->get(Request::class);
		$auth = new Authentication($request);
		$auth->run();
		//var_dump($auth->isSuccess());
	}
}