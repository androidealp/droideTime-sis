
<?php 
use yii\bootstrap\ActiveForm;

$this->title = 'Meus dados';
$this->params['breadcrumbs'][] = $this->title;
 ?>



<div class="row">
	<div class="col-md-3">
		<?php echo $this->render('_menu',['active'=>'dados']); ?>
	</div>
	<div class="col-md-9">

			<div class="panel panel-default">
				<div class='panel-heading'>
					<span>Meus dados</span>
				</div>
				<div class="panel-body">

					<?php if ($session->hasFlash('success')): ?>

						<div class="alert alert-success">
							<p>Este recurso é só para exemplificar a funcionalidade de modificação de senha, não afeta o conteudo da base de dados</p>
						</div>

					<?php else: ?>

						 <?php

                $form = ActiveForm::begin([
                    'id'=>'form-criar',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => 'col-sm-3',
                            'offset' => 'col-md-offset-8',
                            'wrapper' => 'col-sm-12',
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
                ]);

                ?>

                <p class="col-md-12"><?php echo $model->usuario; ?></p>

                <?= $form->field($model, 'senha')->passwordInput(['class'=>'form-control', 'placeholder'=>'Senha']);?>

                <?= $form->field($model, 'redefinir_senha')->passwordInput(['class'=>'form-control', 'placeholder'=>'Digite a senha novamente']);?>

                <button type="submit" class="btn btn-primary pull-right">Editar</button>

                

                <?php ActiveForm::end(); ?>

						
					<?php endif ?>

					
					

				</div>
				<!-- end body -->
			</div>
		
	</div>
	
</div>


