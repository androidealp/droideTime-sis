
<?php 
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Projetos';
$this->params['breadcrumbs'][] = $this->title;

$sumario = <<<HTML
<div class="dataTables_info">
De <span class="">{begin}</span> - <span class="">{end}</span> total de itens <span class="">{totalCount}</span>
</div>

HTML;

 ?>



<div class="row">
	<div class="col-md-3">
		<?php echo $this->render('_menu',['active'=>'projetos']); ?>
	</div>
	<div class="col-md-9">

			<div class="panel panel-default">
				<div class='panel-heading'>
					<span>Projetos</span>
					<div class="pull-right">
						
						<div class="btn-group">
							<a href="#" class="btn btn-success btn-xs"
							data-btaddurl='<?=Url::to(['painel/criar-projeto'])?>'
							data-modalsize= 'md',
							data-formid='form-criar',
							>Criar</a>

							<a href="#" class="btn btn-danger btn-xs"
							data-btdelurl='<?=Url::to(['painel/deletar-projeto'])?>'
							data-btconfirm='Ao deletar um projeto você remove todos os itnes vinculados a ele, deseja continuar?',
		 					title='Deletar',
		 					data-gridid = 'grid-default', 
		 					data-modalsize ='md', 
							>Deletar</a>
						</div>

					</div>
				</div>
				<div class="panel-body">
					

					<?php //Pjax::begin(['id'=>'list-user','options'=>['class'=>"x_content"]]); ?>


        <?= GridView::widget([
        'id'=>'grid-default',
        'dataProvider' => $dataprovider,
        'filterModel' => $model,
        'tableOptions' => ['class' => 'table table-striped projects'],
        'summary'=>$sumario,
        'pager'=>[
          'pageCssClass'=>'pagination',
          'hideOnSinglePage'=>false
        ],
        'layout'=>"{items}<div class='row'><div class='col-sm-5'>{summary}</div><div class='col-sm-7'><div class='dataTables_paginate paging_simple_numbers'>{pager}</div></div></div>",
        'columns' => [
             [
                  'attribute' => 'id',
                  'format' => 'text',
              ],
              [
                  'attribute' => 'nome',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width:25%'],
                  'value'=>function($data){

                  	$html = Html::a(Html::encode($data->nome),['painel/detalhes','id'=>$data->id]);
                       

                    return $html;
                  }

              ],

              [
                  'header'=>"Total consumido",
                  'format' => 'raw',
                  'value'=>function($data){
                    return $data->time_total.' mins';
                    },
                    
              ],

              [
                'class' => 'yii\grid\CheckboxColumn',
                // usar var keys = $('#grid').yiiGridView('getSelectedRows'); para os iscripts
              ]
          ],
        ]);?>

          <?php// Pjax::end(); ?>

				</div>
				<!-- end body -->
			</div>
		
	</div>
	
</div>