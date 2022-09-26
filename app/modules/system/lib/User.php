<?php

namespace App\Modules\System;

class User
{
	protected int $id;
	protected string $name;
	protected string $email;
	protected string $phone;
	protected string $registerDate;
	protected string $accessLevel;

	protected Db $db;
	protected Session $session;

	protected array $dbErrors = [
		'23000' => 'Такой Email или номер телефона уже занят'
	];

	public function __construct(Db $db, Session $session)
	{
		$this->db = $db;
		$this->session = $session;
		/*$userId = $session->get('USER');
		$userParameters = $db->sqlExecution("SELECT * FROM `users` WHERE `id` = :id", [$userId['id']]);
		print_r($userParameters);*/
	}

	public function isAuthorized()
	{

	}

	public function authorize()
	{
		$httpContext = Container::getInstance()->get(HttpContext::class);
		$email = $httpContext->getPostOption('email');
		$password = $httpContext->getPostOption('password');

	}

	public function registration()
	{
		$httpContext = Container::getInstance()->get(HttpContext::class);
		$login = $httpContext->getPostOption('login');
		$name = $httpContext->getPostOption('name');
		$email = $httpContext->getPostOption('email');
		$phone = $httpContext->getPostOption('phone');
		$password = $httpContext->getPostOption('password');
		$confirmedPassword = $httpContext->getPostOption('confirmedPassword');
		$personalData = $httpContext->getPostOption('personalData');

		$errors = [];

		if(!(bool)$personalData)
		{
			$errors[] = "Нужно согласиться на обработку персональных данных!";
		}
		if($password != $confirmedPassword)
		{
			$errors[] = "Пароли не совпадают!";
		}
		if(!Validation::nameValidate($name))
		{
			$errors[] = "Имя должно состоять минимум из двух слов!";
		}
		if(!Validation::emailValidate($email))
		{
			$errors[] = "Неккоректный email!";
		}
		if(!Validation::phoneValidate($phone))
		{
			$errors[] = "Неккоректный номер телефона!";
		}
		if(!Validation::passwordValidate($password))
		{
			$errors[] = "Слишком простой пароль!";
		}
		try {
			$password = password_hash($password, PASSWORD_BCRYPT);
			$registerDate = date('Y-m-j G:i:s');
			$this->db->sqlExecution("INSERT INTO `users` (`name`, `email`, `phone`, `password`, `register_date`) VALUES (:name, :email, :phone, :password, :register_date);", [$name, $email, $phone, $password, $registerDate]);
		}catch (\Exception $exception)
		{
			$errors[] = $this->dbErrors[$exception->getCode()];
		}
		if($errors)
		{
			return [
				'input' => [
					'login' => $login,
					'name' => $name,
					'phone' => $phone,
					'email' => $email,
				],
				'errors' => $errors
			];
		}
		header('Location: /megasport/signin/');
		die();
	}
}