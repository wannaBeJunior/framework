<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/modules/system/lib/Application.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/modules/system/lib/Psr4Autoloader.php";
use App\Modules\System\Application;
(new Application())->run();