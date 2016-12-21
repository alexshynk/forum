<?php
namespace  app\models;

use yii;
use yii\base\Model;
use yii\base\Exception;

class ChPasswForm extends Model{

	public $password_old;
	public $password_new;
	public $password_conf;
	
	public function rules(){
		return[
			[['password_old','password_new', 'password_conf'],'required'],
			['password_conf','compare','compareAttribute' =>'password_new'],
		];	
	}
	
	public function attributeLabels(){
		return [
			'password_old'=>'Старий пароль',
			'password_new'=>'Новый пароль',
			'password_conf'=>'Подтверждение пароля'
		];
	}
	
	public function chPassw(){
		//получаем экземпляр класса Users - текущего пользователя
		$user = Yii::$app->user->identity;
		
		//проверить текущий пароль
		if (!($user->validatePassword($this->password_old))){
			$this->addError('password_old','Не верный текущий пароль');
			return false;
		}
		
		//сменить пароль
		$user->setPassword($this->password_new);
		
		//сохранить изменения
		return $user->save() ? $user : null;
	}
	
}