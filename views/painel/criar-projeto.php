<?php
use yii\bootstrap\ActiveForm;

$urlselAjax =  \yii\helpers\Url::to(['gerenciadorconteudo/load-seletor-item-menu']); 

?>

<?php
  $form = ActiveForm::begin([
      'id'=>'form-criar',
      'layout' => 'default',
      'fieldConfig' => [
          'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
          'horizontalCssClasses' => [
              'label' => 'col-sm-3',
              'offset' => 'col-md-offset-8',
              'wrapper' => 'col-sm-9',
              'error' => '',
              'hint' => '',
          ],
      ],
  ]);

?>


    <?= $form->field($model, 'nome')->label(false)->textInput([
      'class'=>'form-control form-control-sm',
      'placeholder'=>'Nome'
    ])?>


<?php ActiveForm::end(); ?>

