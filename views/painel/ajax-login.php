<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>


    <p class="text-danger">Os dados de acesso estão no documento enviado em anexo</p>

    <?php $form = ActiveForm::begin([
        'id' => 'form-login',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder'=>'Nome']) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Senha']) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-12\">{input} {label}</div>\n<div class=\"col-lg-12\">{error}</div>",
        ])->label('Lembrar meu acesso') ?>


    <?php ActiveForm::end(); ?>

</div>
