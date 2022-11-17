<?php

use App\Modules\System\Autoloader\Entity;

return [
	new Entity('App\Modules\System\\', 'app/modules/system/lib/'),
	new Entity('App\Modules\System\Validator\\', 'app/modules/system/lib/validator'),
	new Entity('App\Modules\System\Validator\Rules\\', 'app/modules/system/lib/validator/rules'),
	new Entity('App\Controllers\\', 'app/controllers/'),
	new Entity('App\Modules\System\\', 'app/modules/system/lib'),
	new Entity('App\Modules\System\Controller\\', 'app/modules/system/lib/controller'),
	new Entity('App\Modules\System\Configuration\\', 'app/modules/system/lib/configuration'),
	new Entity('App\Modules\System\Container\\', 'app/modules/system/lib/container'),
	new Entity('App\Modules\System\DataBase\\', 'app/modules/system/lib/database'),
	new Entity('App\Modules\System\DataBase\Queries\\', 'app/modules/system/lib/database/queries'),
	new Entity('App\Modules\System\Router\\', 'app/modules/system/lib/router'),
	new Entity('App\Modules\System\Session\\', 'app/modules/system/lib/session'),
	new Entity('App\Modules\System\View\\', 'app/modules/system/lib/view'),
	new Entity('App\Modules\System\Exceptions\Interfaces\\', 'app/modules/system/lib/exceptions/interfaces'),
	new Entity('App\Modules\System\Exceptions\\', 'app/modules/system/lib/exceptions'),
];