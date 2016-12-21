<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<!-- ?php $this->registerCssFile('css/add.css');?-->
<link rel="stylesheet" type="text/css" href="css/add.css"/>

<table >
<tr>
<td style="width: 50%; text-align: center;"><b>Информация о форуме</b></td>
<td style="width: 10%; text-align: center;"><b>Тем</b></td>
<td style="width: 10%; text-align: center;"><b>Ответов</b></td>
<td style="width: 30%; text-align: center;"><b>Последняя</b></td>
</tr>

<?php 
foreach ($sections as $section){
	$section_ = htmlspecialchars($section["section"]);
	echo <<<EOD
<tr>
<td><a href="?r=forum/themes&section_id={$section["section_id"]}">{$section_}</a></td>
<td style="text-align: center;">{$section["theme_count"]}</td>
<td style="text-align: center;">{$section["post_count"]}</td>
<td>{$section["last_theme"]}</td>
</tr>
EOD;
}
?>
</table>
<hr/>

<?php if ((!Yii::$app->user->isGuest) && (Yii::$app->user->identity->role == 1)){?>
<?php echo "<div style='color: red;'>{$err_msg}</div>"; ?>
<?php $form=ActiveForm::begin()?>
<?= $form->field($model, 'caption')->textInput()->label(false) ?>
<?= HTML::submitButton('Добавить форум',['class'=>'btn btn-primary'])?>
<?php ActiveForm::end()?>
<?php }?>