<?php
namespace app\models;

use Yii;
use yii\base\Model;

class ThemeForm extends Model{
	public $caption;
	public $text;
	
	public function rules(){
		return [
			[['caption','text'],'required','message'=>'Поле обязательно для ввода'],
			['caption', 'string', 'min'=>5, 'max'=>120, 'tooShort'=>'не менее 5 символов', 'tooLong'=>'не более 120 символов'],			
			['text','string','min'=>10, 'tooShort'=>'не менее 10 символов'],
		];
	}
	
}