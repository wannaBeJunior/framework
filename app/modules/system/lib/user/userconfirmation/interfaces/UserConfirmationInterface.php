<?php

namespace App\Modules\System\User\UserConfirmation\Interfaces;

interface UserConfirmationInterface
{
	public function confirm(int $userId);
}