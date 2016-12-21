<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div style="color:red;">
<?php echo $model->err_msg;?>
</div><br>

<?php $form = ActiveForm::begin(['options'=>['style'=>'max-width: 300px;']]); ?>

<?= $form->field($model, 'username', ['template'=>'{label} *{input}{error}{hint}'])->textInput();?>

<?= $form->field($model, 'password', ['template'=>'{label} *{input}{error}{hint}'])->passwordInput();?>

<?= $form->field($model, 'email', ['template'=>'{label} *{input}{error}{hint}'])->textInput();?>

<?= $form->field($model, 'firstName')->textInput();?>

<?= $form->field($model, 'surName')->textInput();?>

<div class="form-group">
<?= HTML::submitButton('Регистрация',['class'=>'btn btn-primary']);?>
</div>
<?php ActiveForm::end();?>
