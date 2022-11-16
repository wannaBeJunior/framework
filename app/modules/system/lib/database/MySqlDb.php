<?php

namespace App\Modules\System\DataBase;

use App\Modules\System\Configuration\Configuration;

class MySqlDb extends DataBase
{
	function __construct(Configuration $configuration)
	{
		try {
			$databaseConfiguration = $configuration->getDatabaseConfiguration();
			$dsn = "{$databaseConfiguration['driver']}:host={$databaseConfiguration['host']};dbname={$databaseConfiguration['database']};charset={$databaseConfiguration['charset']}";
			$opt = [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
				\PDO::ATTR_EMULATE_PREPARES => false
			];
			$this->pdo = new \PDO($dsn, $databaseConfiguration['user'], $databaseConfiguration['password'], $opt);
		}catch (\Exception $exception)
		{
			echo $exception->getMessage();
		}
	}
}