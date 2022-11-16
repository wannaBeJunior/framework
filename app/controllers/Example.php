<?php
namespace App\Controllers;
use App\Modules\System\Container\Container;
use App\Modules\System\Controller\ControllerInterface;
use App\Modules\System\User;
use App\Modules\System\Validator\Rules\Email;
use App\Modules\System\Validator\Rules\Length;
use App\Modules\System\Validator\Rules\Password;
use App\Modules\System\Validator\Rules\Phone;
use App\Modules\System\Validator\Rules\Required;
use App\Modules\System\Validator\Validator;

class Example implements ControllerInterface
{
	public function example()
	{
		$container = Container::getInstance();

		$form = [
			'login' => 'JohnDoe120',
			'email' => 'muhin.evgeniy.2000@mail.com',
			'password' => 'Ro13!',
			'phone' => '+7 (987) 418-80-56'
		];
		$rules = [
			'login' => [
				new Length(5, 15),
				new Required()
			],
			'email' => [
				new Email()
			],
			'password' => [
				new Password(),
				new Length(6, 15),
			],
			'phone' => [
				new Phone()
			]
		];
		$res = Validator::run($form, $rules);
		/*$result = (new InsertQuery())
			->setTableName('test')
			->setFields(['test1', 'test2'])
			->setValues([':test1', ':test2'])
			->setParams(['1', '2'])
			->execution();*/
		/*$users = (new SelectQuery())
			->setSelect('id')
			->setSelect('name')
			->setTableName('users')
			->setJoin([
				'type' => 'right',
				'ref_table' => 'basket',
				'on' => 'this.id = ref.user'
			])
			->setJoin([
				'type' => 'left',
				'ref_table' => 'product',
				'on' => 'basket.id = ref.basket'
			])
			->setWhere([
				'logic' => 'and',
				'condition' => 'id = :id'
			])
			->setWhere([
				'logic' => 'or',
				'condition' => 'name = :name'
			])
			->setGroupBy('basket.name')
			->setLimit([1, 10])
			->setParams([1, 'test'])
			->execution();*/
		//SELECT * FROM users JOIN basket ON users.id = basket.user WHERE id = 3 AND name = 'name';
		/*$user = $container->get(User::class);
		$userFields = [
			'NAME' => 'Franklin Roozevelt',
			'EMAIL' => 'admin@hermes.com',
			'PHONE' => '+79874188056',
			'LOGIN' => 'admin',
			'PASSWORD' => 'SuperSecret22!',
			'CONFIRMED_PASSWORD' => 'SuperSecret22!',
			'PERSONAL_DATA_AGREEMENT' => 1
		];
		$res = $user->register($userFields);*/
		echo '<pre>';
		var_dump($res);
		echo '</pre>';
	}
}