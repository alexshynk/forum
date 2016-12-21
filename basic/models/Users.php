<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "forum_users".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property string $created
 * @property string $email
 * @property integer $role
 * @property integer $state
 * @property string $firstName
 * @property string $surName
 */

class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forum_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'authKey', 'accessToken'], 'required'],
            [['created'], 'safe'],
            [['role', 'state'], 'integer'],
            [['username'], 'string', 'max' => 30],
            [['password', 'authKey', 'accessToken', 'email'], 'string', 'max' => 120],
            [['firstName', 'surName'], 'string', 'max' => 50],
            [['username'], 'unique','message' => 'Это имя пользователя занято']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'user id',
            'username' => 'user\'s login',
            'password' => 'user\'s password',
            'authKey' => 'auth key column - required for cookie based login, should be unique for every user',
            'accessToken' => 'acces token column - required for REST autenticacion',
            'created' => 'date of registration',
            'email' => 'email of user',
            'role' => '1 - admin, 0 - ordinary user',
            'state' => 'user\'s state: 1 - active, 0 - not active',
            'firstName' => 'first name of user',
            'surName' => 'sur name of user',
        ];
    }
    
    //-------------------------------------------
    // Методы для генерации значений полей для нового пользователя
    //-------------------------------------------
    //генерирует случайную строку (32 символа) для authKey
    public function generateAuthKey(){
    	$this->authKey = Yii::$app->security->generateRandomString();
    }
    
    //формируем password's hash(поле password) для введенного пользователем пароля
    public function setPassword($password){
    	$this->password = Yii::$app->security->generatePasswordHash($password);
    }
    
    
    //-------------------------------------------
    // Методы IdentityInterface
    //-------------------------------------------
    public static function findIdentity($id)
    {
    	return static::findOne($id,['state' => 1]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
    	return static::findOne(['access_token' => $token]);
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getAuthKey()
    {
    	return $this->authKey;
    }
    
    public function validateAuthKey($authKey)
    {
    	return $this->authKey === $authKey;
    }
    
    public static function findByUsername($username)
    {
    	return self::findOne(['username' => $username, 'state' => 1]);
    }
    
    public function validatePassword($password)
    {
    	// валидация на случай если пароль из БД не был зашифрован
    	if ($password === $this->password) return true; 
    	
    	//валидация на случай если пароль был зашифрован
    	try{
    		return Yii::$app->security->validatePassword($password, $this->password);
    	}catch(\yii\base\InvalidParamException $e){
    		return false;
    	}
    }    
}
