<?php

use App\Modules\System\Autoloader\Entity;

return [
	new Entity('App\Modules\System\\', 'app/modules/system/lib/'),
	new Entity('App\Modules\System\Validator\\', 'app/modules/system/lib/validator'),
	new Entity('App\Modules\System\Validator\Rules\\', 'app/modules/system/lib/validator/rules'),
	new Entity('App\Controllers\\', 'app/controllers/'),
	new Entity('App\Modules\System\\', 'app/modules/system'),
];