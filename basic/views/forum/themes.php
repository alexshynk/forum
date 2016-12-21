<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<link rel="stylesheet" type="text/css" href="css/add.css"/>

<?php 
echo <<<EOD
<span style='font-weight: bold; color: blue;'>
<a href="?r=site/index">Форумы</a> -> 
<a href="?r=forum/themes&section_id={$section["section_id"]}">{$section["section"]}</a>
</span>
<hr/>
EOD;
?>

<?php
if (!Yii::$app->user->isGuest)
echo <<<EOD
<input class="" id="btn-new_theme" type="button" value="Новая тема" onclick="$('#new_theme').show(); $('#btn-new_theme').hide();"/>
EOD;
?>

<div id="new_theme">
<?php $form=ActiveForm::begin()?>
<?= $form->field($model, 'caption')->textInput(['placeHolder'=>'Название темы'])->label(false); ?>
<?= $form->field($model, 'text')->textarea(['rows'=>'7','style'=>'resize: none; overflow: auto;'])->label(false); ?>
<?= HTML::submitButton('Создать тему',['class'=>'btn btn-primary']) ?>&nbsp;&nbsp;
<?= HTML::button('Отмена',['class'=>'btn cancel', 'onclick'=>'$("#new_theme").hide(); $("#btn-new_theme").show();']); ?>
<?php ActiveForm::end()?>
</div>
<hr/>

<table>
<tr class="head">
<td style="width: 50%; text-align: center;">Тема</td>
<td style="width: 10%; text-align: center;">Дата</td>
<td style="width: 10%; text-align: center;">Автор</td>
<td style="width: 10%; text-align: center;">Ответов</td>
<td style="width: 20%; text-align: center;">Последний</td>
</tr>
<?php
foreach ($themes as $theme){
$theme_ = htmlspecialchars($theme["theme"]);
	echo <<<EOD
<tr>
<td><a href="?r=forum/posts&theme_id={$theme["theme_id"]}">{$theme_}</a></td>
<td>{$theme["theme_date"]}</td>
<td>{$theme["theme_creator"]}</td>
<td>{$theme["post_count"]}</td>
<td>{$theme["last_post"]}</td>
</tr>
EOD;
}
?>
</table>
