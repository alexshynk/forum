<?php
namespace  app\models;

use yii;
use yii\base\Model;

class RegUserForm extends Model{
	public $username;
	public $password;
	public $email;
	public $firstName;
	public $surName;
	
	public $err_msg;
	
	
	public function rules(){
		return[
			[['username','password','email'],'required'],
			[['firstName','surName'],'string'],
			['email','email']
		];	
	}
	
	public function attributeLabels(){
		return [
			'username'=>'Логин',
			'password'=>'Пароль',
			'firstName'=>'Имя',
			'surName'=>'Фамилия'
		];
	}
	
	public function regUser(){
		$user = new Users();
		$user->username = $this->username;
		$user->setPassword($this->password);
		$user->generateAuthKey();
		$user->accessToken = $user->authKey;
		$user->role = 0;
		$user->email = $this->email;
		$user->firstName = $this->firstName;
		$user->surName = $this->surName;		
		
		//проверяем - проходят ли валидацию введенные значения
		if (!$user->validate()){
			$this->err_msg = 'Ошибка при регистрации нового пользователя';
			
			//получить список ошибок
			$errors = $user->errors;

			//добавить ошибки
			foreach ($errors as $attr => $error) 
				$this->addError($attr,implode(', ',$error));
			
			return false;
		}
		
		return $user->save() ? $user : null;
	}
	
	public function sendActivationEmail(){
		//формируем формируем письмо для отправки пользователю
		$subject = "Регистрация на форуме";
		$body = "Поздравляем {$this->username} ({$this->firstName} {$this->surName}).\r\n Вы были успешно зарегиcтрированы на форуме.";
		$headers = "Content-type:text/text; charset=utf-8";

		//отправляем письмо - настройки почтового сервера на хостинге
		mail($this->email, $subject, $body, $headers, "-fwww@{$_SERVER["HTTP_HOST"]}");
	}
	
}