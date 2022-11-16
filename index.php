<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/modules/system/lib/Application.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/modules/system/lib/autoloader/Autoloader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/modules/system/lib/autoloader/EntityInterface.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/modules/system/lib/autoloader/Entity.php";

use App\Modules\System\Application;
(new Application())->run();