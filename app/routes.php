<?php

use App\Modules\System\Router\Route;

return [
	new Route('example/{id}/{code}', 'Example', 'example', 'GET'),
];