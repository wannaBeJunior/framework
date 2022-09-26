<?php

namespace App\Modules\System;

class Validation
{
	static public function emailValidate(string $email)
	{
		return preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $email);
	}

	static public function phoneValidate(string $phone) : bool
	{
		return preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $phone);
	}

	static public function loginValidate(string $login) : bool
	{
		return mb_strlen($login) > 5;
	}

	static public function passwordValidate(string $password) : bool
	{
		return preg_match("/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}$/", $password);
	}

	static public function nameValidate(string $name) : bool
	{
		return preg_match("/^([а-яА-Я ]{3,30}|[a-zA-Z ]{2,30}){2}$/", $name);
	}
}