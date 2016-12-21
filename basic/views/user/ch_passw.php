<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'password_old',['template'=>'{label} *{input}{error}{hint}'])->passwordInput(['style'=>'max-width: 300px;']);?>

<?= $form->field($model, 'password_new', ['template'=>'{label} *{input}{error}{hint}', 'options'=>['style'=>'max-width: 300px;']])->passwordInput();?>
<?= $form->field($model, 'password_conf',['template'=>'{label} *{input}{error}{hint}', 'options'=>['style'=>'max-width: 300px;']])->passwordInput();?>

<div class="form-group">
<?= HTML::submitButton('Именить пароль',['class'=>'btn btn-primary']);?>
</div>
<?php ActiveForm::end();?>