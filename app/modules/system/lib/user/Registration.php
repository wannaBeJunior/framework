<?php

namespace App\Modules\System\User;

use App\Modules\System\DataBase\DataBaseResult;
use App\Modules\System\DataBase\Queries\InsertQuery;
use App\Modules\System\Logger\Logger;
use App\Modules\System\Options\Options;
use App\Modules\System\Request\Request;
use App\Modules\System\User\UserConfirmation\Interfaces\UserConfirmationInterface;
use App\Modules\System\Validator\Rules\Email;
use App\Modules\System\Validator\Rules\Login;
use App\Modules\System\Validator\Rules\Password;
use App\Modules\System\Validator\Rules\Phone;
use App\Modules\System\Validator\Rules\Regex;
use App\Modules\System\Validator\Validator;

class Registration
{
	protected Logger $logger;
	protected const DUPLICATE_ERROR_CODE = 23000;
	protected array $errors;
	protected Request $request;
	protected UserConfirmationInterface $userConfirmation;

	public function getErrors(): array
	{
		return $this->errors;
	}

	public function isSuccess(): bool
	{
		return (bool) count($this->errors);
	}

	public function __construct(Request $request, UserConfirmationInterface $userConfirmation = null)
	{
		$this->logger = new Logger();
		$this->request = $request;
		if($userConfirmation)
		{
			$this->userConfirmation = $userConfirmation;
		}
	}

	public function run()
	{
		$data = $this->setDataKeysToUpperCase($this->request->getPostParameters());

		if(!$data)
		{
			$this->logger->warning("В метод регистрации пользователя передан пустой массив полей.");
			return;
		}

		$this->checkRequiredFields();
		$this->validateFields();

		//todo: реализовать доп. поля пользователя и валидацию по ним.

		if(!isset($this->errors))
		{
			$userInsert = $this->userInsert();
			if($userInsert->isSuccess())
			{
				$this->userConfirm($userInsert);
			}else
			{
				if($userInsert->getErrorCode() == static::DUPLICATE_ERROR_CODE)
				{
					$this->errors[] = [
						'field' => '',
						'message' => 'Такой пользователь уже существует'
					];
				}else
				{
					$this->errors[] = [
						'field' => '',
						'message' => 'Непредвиденная ошибка'
					];
				}
			}
		}
	}

	/**
	 * Приводит ключи элементов массива к верхнему регистру
	 * @param array $data
	 * @return array
	 */
	protected function setDataKeysToUpperCase(array $data): array
	{
		$newData = [];
		foreach ($data as $key => $value)
		{
			if(is_array($value))
			{
				$newData[] = $this->setDataKeysToUpperCase($value);
				continue;
			}
			$newData[mb_strtoupper($key)] = $value;
		}
		return $newData;
	}

	/**
	 * Проверяет, что все обязательные поля заполнены
	 */
	protected function checkRequiredFields(): void
	{
		$data = $this->setDataKeysToUpperCase($this->request->getPostParameters());
		$requiredFields = Options::getOption('required_user_fields');
		foreach ($requiredFields['values'] as $requiredField)
		{
			if(!in_array($requiredField['code'], array_keys($data)))
			{
				$this->errors[] = [
					'field' => $requiredField['code'],
					'message' => 'Не было заполнено обязательное поле'
				];
			}
		}
	}

	/**
	 * Валидирует введенные пользователем поля
	 */
	protected function validateFields()
	{
		$data = $this->setDataKeysToUpperCase($this->request->getPostParameters());
		$fields = [];
		$rules = [];

		if(isset($data['LOGIN']) && Options::getOption('login_validate'))
		{
			if($pattern = Options::getOption('login_validate_rule')['value'])
			{
				$rules['LOGIN'][] = new Regex($pattern);
			}else
			{
				$rules['LOGIN'][] = new Login();
			}
			$fields['LOGIN'] = $data['LOGIN'];
		}
		if(isset($data['EMAIL']) && Options::getOption('email_validate'))
		{
			if($pattern = Options::getOption('email_validate_rule')['value'])
			{
				$rules['EMAIL'][] = new Regex($pattern);
			}else
			{
				$rules['EMAIL'][] = new Email();
			}
			$fields['EMAIL'] = $data['EMAIL'];
		}
		if(isset($data['PHONE']) && Options::getOption('phone_validate'))
		{
			if($pattern = Options::getOption('phone_validate_rule')['value'])
			{
				$rules['PHONE'][] = new Regex($pattern);
			}else
			{
				$rules['PHONE'][] = new Phone();
			}
			$fields['PHONE'] = $data['PHONE'];
		}
		if(isset($data['NAME']))
		{
			$rules['NAME'][] = new Regex('/^[\p{L}\p{M} ]+$/u');
			$fields['NAME'] = $data['NAME'];
		}else
		{
			$data['NAME'] = '';
		}
		if(isset($data['PASSWORD']) && $data['PASSWORD'])
		{
			$rules['PASSWORD'][] = new Password();
			$fields['PASSWORD'] = $data['PASSWORD'];
		}else
		{
			$this->errors[] = [
				'field' => 'PASSWORD',
				'message' => 'Не было заполнено обязательное поле'
			];
		}

		$validation = Validator::run($fields, $rules);
		if(in_array(false, $validation))
		{
			foreach ($validation as $validatedField => $validateResult)
			{
				if(!$validateResult)
				{
					$this->errors[] = [
						'field' => $validatedField,
						'message' => 'Невалидный ввод'
					];
				}
			}
		}

		if($data['PASSWORD'] != $data['REPEATED_PASSWORD'])
		{
			$this->errors[] = [
				'field' => 'REPEATED_PASSWORD',
				'message' => 'Пароли не совпадают'
			];
		}
	}

	/**
	 * Запись пользователя в БД
	 * @return DataBaseResult
	 */
	public function userInsert(): DataBaseResult
	{
		$data = $this->setDataKeysToUpperCase($this->request->getPostParameters());

		return (new InsertQuery())
			->setTableName('users')
			->setFields(['name', 'email', 'login', 'password', 'register_date', 'access_level', 'phone'])
			->setValues([':name', ':email', ':login', ':password', ':register_date', ':access_level', ':phone'])
			->setParams([
				'name' => $data['NAME'] ?? '',
				'email' => $data['EMAIL'] ?? '',
				'login' => $data['LOGIN'] ?? '',
				'password' => password_hash($data['PASSWORD'], PASSWORD_BCRYPT),
				'register_date' => date('Y-m-d H:i:s'),
				'access_level' => Options::getOption('default_user_access_level')['value'],
				'phone' => $data['PHONE'] ?? '',
			])
			->execution();
	}

	/**
	 * Производит подтверждение аккаунта пользователя если такая настройка включена
	 * @param DataBaseResult $userInsert
	 */
	protected function userConfirm(DataBaseResult $userInsert)
	{
		$userId = $userInsert->getLastInsertedId();
		if(Options::getOption('need_user_confirm')['value'] && isset($this->userConfirmation))
		{
			$this->userConfirmation->confirm($userId);
		}
	}
}