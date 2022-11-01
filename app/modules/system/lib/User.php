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
	protected Settings $settings;

	protected const TABLE_NAME = 'users';

	public function __construct(DataBase $db, Session $session, Settings $settings)
	{
		$this->db = $db;
		$this->session = $session;
		$this->settings = $settings;
	}

	public function isAuthorized(): bool
	{
		return $this->session->has('USER');
	}

	public function authorize(string $login, string $password): array
	{
		$errors = [];

		try {
			if($this->isAuthorized())
			{
				throw new \Exception('Пользователь уже авторизован!');
			}
			$filter = [
				'LOGIC' => 'OR',
				'FIELDS' => [
					'EMAIL' => $login,
					'LOGIN' => $login,
					'PHONE' => $login
				]
			];
			$user = $this->getUserByFilter($filter);
			if($user->getRowsCount() > 0)
			{
				$userData = $user->getResult();
				if(password_verify($password, $userData['password']))
				{
					$userSessionParameters = [
						'id' => $userData['id'],
					];
					$this->id = $userSessionParameters['id'];
					$this->session->set('USER', $userSessionParameters);
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

		$result = [
			'errors' => $errors
		];

		if(!$errors)
		{
			$result['redirect'] = $this->settings->getBySettingName('REDIRECT_AFTER_AUTH');
		}

		return $result;
	}

	public function getUserByFilter(array $filter): DataBaseResult
	{
		$sql = "SELECT * FROM {self::TABLE_NAME} ";
		$logic = '';
		$sqlParams = [];

		if(count($filter['FIELDS']) > 1)
		{
			$logic = $filter['LOGIC'];
		}

		foreach($filter['FIELDS'] as $fieldName => $fieldValue)
		{
			$sqlParams[] = $fieldValue;
			$fieldName = strtolower($fieldName);
			$sql .= "`{$fieldName}` = {$fieldValue} {$logic}";
		}

		$sql = "SELECT * FROM `users` WHERE `email` = :email OR `login` = :login OR `phone` = :phone";
		return $this->db->query($sql, $sqlParams);
	}

	public function register(array $userFields): array
	{
		$errors = [];

		$filter = [
			'LOGIC' => 'OR',
			'FIELDS' => [
				'EMAIL' => $userFields['EMAIL'],
				'LOGIN' => $userFields['LOGIN'],
				'PHONE' => $userFields['PHONE']
			]
		];
		$user = $this->getUserByFilter($filter);

		if($user->getRowsCount())
		{
			$errors[] = "Пользователь с такими логином, телефоном или email уже существует!";
		}
		if(!(bool)$userFields['PERSONAL_DATA_AGREEMENT'])
		{
			$errors[] = "Нужно согласиться на обработку персональных данных!";
		}
		if($userFields['PASSWORD'] != $userFields['CONFIRMED_PASSWORD'])
		{
			$errors[] = "Пароли не совпадают!";
		}
		if(!Validation::nameValidate($userFields['NAME']))
		{
			$errors[] = "Имя должно состоять минимум из двух слов!";
		}
		if(!Validation::emailValidate($userFields['EMAIL']))
		{
			$errors[] = "Неккоректный email!";
		}
		if(!Validation::phoneValidate($userFields['PHONE']))
		{
			$errors[] = "Неккоректный номер телефона!";
		}
		if(!Validation::passwordValidate($userFields['PASSWORD']))
		{
			$errors[] = "Слишком простой пароль!";
		}

		try {
			if(!$errors)
			{
				$password = password_hash($userFields['PASSWORD'], PASSWORD_BCRYPT);
				$registerDate = date('Y-m-j G:i:s');
				$this->db->query("INSERT INTO `users` (`name`, `email`, `phone`, `login`, `password`, `register_date`) VALUES (:name, :email, :phone, :login, :password, :register_date);", [$userFields['NAME'], $userFields['EMAIL'], $userFields['PHONE'], $userFields['LOGIN'], $password, $registerDate]);
			}
		}catch (\Exception $exception)
		{
			$errors[] = "Что-то пошло не так, но мы уже работаем над этим!";
		}

		$result = [
			'errors' => $errors
		];

		if(!$errors)
		{
			$result['redirect'] = $this->settings->getBySettingName('REDIRECT_AFTER_REGISTER');
		}

		return $result;
	}

	public function getUserId() : int
	{
		if($this->isAuthorized())
		{
			return $this->id;
		}
		return 0;
	}

	public function getName() : string
	{
		$name = '';
		if($this->isAuthorized())
		{
			if(!$this->name)
			{
				$sql = "SELECT `name` FROM `users` WHERE `id` = :id";
				$queryResult = $this->db->query($sql, [$this->id])->getResult();
				$name = $queryResult['name'];
				$this->name = $name;
			}else
			{
				$name = $this->name;
			}
		}

		return $name;
	}

	public function getEmail() : string
	{
		$email = '';
		if($this->isAuthorized())
		{
			if(!$this->email)
			{
				$sql = "SELECT `email` FROM `users` WHERE `id` = :id";
				$queryResult = $this->db->query($sql, [$this->id])->getResult();
				$email = $queryResult['email'];
				$this->email = $email;
			}else
			{
				$email = $this->email;
			}
		}

		return $email;
	}

	public function getPhone() : string
	{
		$phone = '';
		if($this->isAuthorized())
		{
			if(!$this->phone)
			{
				$sql = "SELECT `phone` FROM `users` WHERE `id` = :id";
				$queryResult = $this->db->query($sql, [$this->id])->getResult();
				$phone = $queryResult['phone'];
				$this->phone = $phone;
			}else
			{
				$phone = $this->phone;
			}
		}

		return $phone;
	}

	public function getRegisterDate() : string
	{
		$date = '';
		if($this->isAuthorized())
		{
			if(!$this->registerDate)
			{
				$sql = "SELECT `register_date` FROM `users` WHERE `id` = :id";
				$queryResult = $this->db->query($sql, [$this->id])->getResult();
				$date = $queryResult['register_date'];
				$this->registerDate = $date;
			}else
			{
				$date = $this->registerDate;
			}
		}

		return $date;
	}

	public function getAccessLevel() : int
	{
		$accessLevel = '';
		if($this->isAuthorized())
		{
			if(!$this->accessLevel)
			{
				$sql = "SELECT `access_level` FROM `users` WHERE `id` = :id";
				$queryResult = $this->db->query($sql, [$this->id])->getResult();
				$accessLevel = $queryResult['access_level'];
				$this->accessLevel = $accessLevel;
			}else
			{
				$accessLevel = $this->accessLevel;
			}
		}

		return $accessLevel;
	}
}