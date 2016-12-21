<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\RegUserForm;
use app\models\ChPasswForm;

class UserController extends Controller{
	
	//зарегистрировать нового пользователя
	public function actionRegister(){
		$model = new RegUserForm();
		
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($user = $model->regUser()){
				if (Yii::$app->getUser()->login($user)){
					$model->sendActivationEmail();
					$this->goHome();
				}
			}
			else return $this->render('register', ['model'=>$model]);
		}
		else return $this->render('register', ['model'=>$model]);
	}
	
	//изменить пароль пользователя
	public function actionCh_passw(){
		$model = new ChPasswForm();
		
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if ($model->chPassw())
				$this->goHome();
			else return $this->render('ch_passw', ['model'=>$model]);
		}
		else return $this->render('ch_passw', ['model'=>$model]);
	}
}