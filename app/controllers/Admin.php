<?php

namespace App\Controllers;

use App\Modules\System\Container\Container;
use App\Modules\System\Controller\ControllerInterface;
use App\Modules\System\Tools\AdminTools;
use App\Modules\System\Tools\GroupsTools;
use App\Modules\System\Tools\UserTools;
use App\Modules\System\View\View;

class Admin implements ControllerInterface
{
	public function Start(array $params)
	{
		$module = $params['module'];

		if(!UserTools::isAuthenticated())
		{
			$this->redirectToPublic();
		}

		if(!GroupsTools::checkAccessRightsByGroupIds($module, UserTools::getCurrentUserGroups()))
		{
			$this->redirectToPublic();
		}

		$modules = AdminTools::getModules();
		/**
		 * @var View $view
		 */
		$view = Container::getInstance()->get(View::class);
		$view->show('settings', [
			'modules' => $modules,
			'current_module' => $module
		]);
	}

	public function RedirectToPanel()
	{
		Header('Location: /admin/settings/system');
		die();
	}

	protected function redirectToPublic()
	{
		Header('Location: /');
		die();
	}
}