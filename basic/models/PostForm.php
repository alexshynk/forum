<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "forum_data".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $caption
 * @property string $date_
 * @property string $text
 * @property integer $user_id
 *
 * @property ForumUsers $user
 * @property PostForm $parent
 * @property PostForm[] $postForms
 */
class PostForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forum_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'user_id'], 'integer'],
            [['date_'], 'safe'],
            [['user_id'], 'required'],
            [['caption'], 'string', 'max' => 120],
        	['text','required','message'=>'Поле обязательно для ввода'],
        	['text', 'string', 'min'=>10, 'tooShort'=>'не менее 10 символов'],        		
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id of section, theme, post',
            'parent_id' => 'Parent ID',
            'caption' => 'Caption',
            'date_' => 'Date',
            'text' => 'Text',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(ForumUsers::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(PostForm::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostForms()
    {
        return $this->hasMany(PostForm::className(), ['parent_id' => 'id']);
    }
}
