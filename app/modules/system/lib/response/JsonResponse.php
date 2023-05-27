<?php

namespace App\Modules\System\Response;

class JsonResponse extends Response
{

	public function send($value = [])
	{
		echo json_encode($value, JSON_UNESCAPED_UNICODE);
	}
}