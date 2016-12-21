<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\base\Exception;
use app\models\ThemeForm;
use app\models\PostForm;

class ForumController extends Controller{
	
	//Открыть страницу тем
	public  function actionThemes($section_id){
		$model = new ThemeForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()){
			$user_id = Yii::$app->user->identity->getId();
			$query = sprintf("call pr_add_forum_theme(@err_code, @err_msg,	@new_theme_id, %d, %d, '%s', '%s')"
					,$section_id ,$user_id, $model->caption, $model->text);
			Yii::$app->db->createCommand($query)->execute();
			
			$query = "select @err_code, @err_msg, @new_theme_id";
			$res = Yii::$app->db->createCommand($query)->queryOne();
			
		    if ($res["@err_code"] == 0){
		    	$url = Url::to(["forum/posts","theme_id"=>$res["@new_theme_id"]]);
		    	return $this->redirect($url);
		    	//return Yii::$app->runAction("forum/posts",["theme_id"=>$res["@new_theme_id"]]);
		    }
		}
		
		//путь к темам
		$query = sprintf("select section_id, section from v_forum_sections where section_id=%d",$section_id);
		$section = Yii::$app->db->createCommand($query)->queryOne();
		
		//список тем
		$query = sprintf("select * from v_forum_themes where section_id = %d",$section_id);
		$themes = Yii::$app->db->createCommand($query)->queryAll();		
		
		return $this->render('themes',['model'=>$model, 'section'=>$section, 'themes'=>$themes]);
	}
	
	//Открыть страницу постов
	public function actionPosts($theme_id){
		$model = new PostForm();
		if($model->load(YII::$app->request->post())){
			//получить id активного пользователя
			$user_id = Yii::$app->user->identity->getId();
			
			$model->user_id = $user_id;
			$model->parent_id = $theme_id;
			$model->validate();
			$model->save();	
			
			return $this->refresh();
		}
		
		//путь к постам
		$query = sprintf("select theme_id, theme, section_id, section, theme_date, theme_creator, theme_text from v_forum_themes where theme_id=%d", $theme_id);
		$theme = Yii::$app->db->createCommand($query)->queryOne();
		
		//список постов
		$query = sprintf("select * from v_forum_posts where theme_id = %d", $theme_id);
		$posts = Yii::$app->db->createCommand($query)->queryAll();		
		
		return $this->render('posts', ['model'=>$model, 'theme'=>$theme, 'posts'=>$posts]);
	}
}