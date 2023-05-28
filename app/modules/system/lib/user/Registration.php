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

class Registration extends BaseUserAction
{
	protected const DUPLICATE_ERROR_CODE = 23000;
	protected UserConfirmationInterface $userConfirmation;

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

		if(!$this->errors)
		{
			$userInsert = $this->userInsert();
			$this->addUserToDefaultGroup($userInsert->getLastInsertedId());
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
			$this->setErrors('PASSWORD', 'Не было заполнено обязательное поле');
		}

		$validation = Validator::run($fields, $rules);
		if(in_array(false, $validation))
		{
			foreach ($validation as $validatedField => $validateResult)
			{
				if(!$validateResult)
				{
					$this->setErrors($validatedField, 'Невалидный ввод');
				}
			}
		}

		if($data['PASSWORD'] != $data['REPEATED_PASSWORD'])
		{
			$this->setErrors('REPEATED_PASSWORD', 'Пароли не совпадают');
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
			->setFields(['name', 'email', 'login', 'password', 'register_date', 'phone'])
			->setValues([':name', ':email', ':login', ':password', ':register_date', ':phone'])
			->setParams([
				'name' => $data['NAME'] ?? '',
				'email' => $data['EMAIL'] ?? '',
				'login' => $data['LOGIN'] ?? '',
				'password' => password_hash($data['PASSWORD'], PASSWORD_BCRYPT),
				'register_date' => date('Y-m-d H:i:s'),
				'phone' => $data['PHONE'] ?? '',
			])
			->execution();
	}

	/**
	 * Пользователь присоединяется к группе по-умолчанию
	 * @param int $userId
	 */
	public function addUserToDefaultGroup(int $userId)
	{
		$defaultGroupId = Options::getOption('default_user_access_level')['value'];
		(new InsertQuery())
			->setTableName('groups_users')
			->setFields(['group', 'user'])
			->setValues([$defaultGroupId, $userId])
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