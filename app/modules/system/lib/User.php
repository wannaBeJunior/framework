<?php

namespace App\Modules\System;

class User
{
	protected string $id;
	protected string $name;
	protected string $email;
	protected string $phone;
	protected string $registerDate;
	protected string $accessLevel;

	protected DataBase $db;
	protected Session $session;

	protected array $dbErrors = [
		'23000' => 'Такой Email или номер телефона уже занят'
	];

	public function __construct(DataBase $db, Session $session)
	{
		$this->db = $db;
		$this->session = $session;
		$this->setUserParameters();
	}

	public function setUserParameters()
	{
		if($this->isAuthorized())
		{
			$userId = $this->session->get('USER');
			$userParameters = $this->db->sqlExecution("SELECT * FROM `users` WHERE `id` = :id", [$userId['id']]);
			$this->setId($userParameters['data'][0]['id']);
			$this->setName($userParameters['data'][0]['name']);
			$this->setEmail($userParameters['data'][0]['email']);
			$this->setPhone($userParameters['data'][0]['phone']);
			$this->setPhone($userParameters['data'][0]['phone']);
			$this->setRegisterDate($userParameters['data'][0]['register_date']);
			$this->setAccessLevel($userParameters['data'][0]['access_level']);
		}
	}
	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function setEmail(string $email) : void
	{
		$this->email = $email;
	}

	public function setPhone(string $phone) : void
	{
		$this->phone = $phone;
	}

	public function setRegisterDate(string $registerDate) : void
	{
		$this->registerDate = $registerDate;
	}

	public function setAccessLevel(string $accessLevel) : void
	{
		$this->accessLevel = $accessLevel;
	}

	public function isAuthorized() : bool
	{
		return $this->session->has('USER');
	}

	public function authorize()
	{
		$httpContext = Container::getInstance()->get(HttpContext::class);
		$email = $httpContext->getPostOption('email');
		$password = $httpContext->getPostOption('password');

		$errors = [];

		if(!Validation::emailValidate($email))
		{
			$errors[] = "Неккоректный email!";
		}
		if(!Validation::passwordValidate($password))
		{
			$errors[] = "Неккоректный пароль!";
		}

		if(!$errors)
		{
			try {
				$sql = "SELECT * FROM `users` WHERE `email` = :email";
				$user = $this->db->sqlExecution($sql, [$email]);
				if($user['data'])
				{
					if(password_verify($password, $user['data'][0]['password']))
					{
						$userSessionParameters = [
							'id' => $user['data'][0]['id'],
							'access_level' => $user['data'][0]['access_level']
						];
						$this->session->set('USER', $userSessionParameters);
						header('Location: /megasport/main');
						die();
					}else
					{
						$errors[] = "Не верный пароль!";
					}
				}else
				{
					$errors[] = "Такой пользователь не найден!";
				}
			}catch (\Exception $exception)
			{
				$errors[] = "Что-то пошло не так, но мы уже работаем над этим!";
			}
		}
		return [
			'input' => [
				'email' => $email
			],
			'errors' => $errors
		];
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

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this -> id;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this -> name;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this -> email;
	}

	/**
	 * @return string
	 */
	public function getPhone(): string
	{
		return $this -> phone;
	}

	/**
	 * @return string
	 */
	public function getRegisterDate(): string
	{
		return $this -> registerDate;
	}

	/**
	 * @return string
	 */
	public function getAccessLevel(): string
	{
		return $this -> accessLevel;
	}
}