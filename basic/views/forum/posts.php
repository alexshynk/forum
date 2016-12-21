<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<link rel="stylesheet" type="text/css" href="css/add.css"/>

<?php 
$theme_ = htmlspecialchars($theme["theme"]);
$theme_text = htmlspecialchars($theme["theme_text"]);

echo <<<EOD
<span style='font-weight: bold; color: blue;'>
<a href="?r=site/index">Форумы</a> -> 
<a href="?r=forum/themes&section_id={$theme["section_id"]}">{$theme["section"]}</a> -> 
<a href="?r=forum/posts&theme_id={$theme["theme_id"]}">{$theme_}</a>
</span>

<div class="message">

<div class="theme_text">
{$theme_text}
</div>

<div class="theme_panel">
{$theme["theme_date"]} от <b>{$theme["theme_creator"]}</b>	
</div>
		
</div><br>
EOD;

foreach ($posts as $post){
	$post_text = htmlspecialchars($post["post_text"]);
	echo <<<EOD
<div class="message">

<div class="post_text">
{$post_text}
</div>

<div class="post_panel">
{$post["post_date"]} от <b>{$post["post_creator"]}</b>	
</div>
		
</div><br>
EOD;
}
?>
<?php if (!Yii::$app->user->isGuest){?>
<div id="new_post">
<hr/>
<?php $form = ActiveForm::begin()?>
<?= $form->field($model, 'text')->textarea(['rows'=>'7','style'=>'resize: none; overflow: auto;'])->label(false) ?>
<?= Html::submitButton('Ответить',['class'=>'btn btn-primary'])  ?>
<?php ActiveForm::end()?>
</div>
<?php }?>