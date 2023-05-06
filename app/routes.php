<?php

use App\Modules\System\Router\Route;

return [
	new Route('example/{id}/{code}', 'Example', 'example', 'GET'),
	new Route('admin/settings/{module}', 'Admin', 'Start', 'GET'),
	new Route('admin/', 'Admin', 'RedirectToPanel', 'GET'),
];