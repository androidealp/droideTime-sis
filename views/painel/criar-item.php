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


    <?= $form->field($model, 'file')->label(false)->textInput([
      'class'=>'form-control form-control-sm',
      'placeholder'=>'Nome do arquivo a ser analisado'
    ])?>

    <?= $form->field($model, 'language')->label(false)->textInput([
      'class'=>'form-control form-control-sm',
      'placeholder'=>'Linguagem de programação a qual trabalha'
    ])?>


    <div class="row">
      <div class="col-md-6">

        <?= $form->field($model, 'date_init')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '99/99/9999',
])->label('inicio em:') ?>
 
      </div>
      
    </div>
    



<?php ActiveForm::end(); ?>

