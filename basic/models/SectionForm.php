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
 * @property SectionForm $parent
 * @property SectionForm[] $sectionForms
 */
class SectionForm extends \yii\db\ActiveRecord
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
            [['text'], 'string'],
            [['user_id'], 'required'],
        	['caption','required','message'=>'Поле обязательно для ввода'],
            ['caption', 'string', 'min'=>5, 'max'=>120, 'tooShort'=>'не менее 5 символов', 'tooLong'=>'не более 120 символов'],	
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
        return $this->hasOne(SectionForm::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionForms()
    {
        return $this->hasMany(SectionForm::className(), ['parent_id' => 'id']);
    }
}
