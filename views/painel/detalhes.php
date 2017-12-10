
<?php 
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Detalhes';
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
					<span>Projetos Detalhes - <?php echo $projeto->nome ?></span>
					<div class="pull-right">
						
						<div class="btn-group">
							<a href="#" class="btn btn-success btn-xs"
							data-btaddurl='<?=Url::to(['painel/criar-item','id_proj'=>$projeto->id])?>'
							data-modalsize= 'md',
							data-formid='form-criar',
							>Criar</a>

							<a href="#" class="btn btn-danger btn-xs"
							data-btdelurl='<?=Url::to(['painel/deletar-item','id_proj'=>$projeto->id])?>'
							data-btconfirm='Ao deletar um projeto você remove todos os itnes vinculados a ele, deseja continuar?',
		 					title='Deletar',
		 					data-gridid = 'grid-default', 
		 					data-modalsize ='md', 
							>Deletar</a>

							<a href="<?=Url::to(['painel/projetos'])?>" class="btn btn-info btn-xs">Voltar</a>
						</div>

					</div>
				</div>
				<div class="panel-body">
					

					<?php Pjax::begin(['id'=>'list-user','options'=>['class'=>"x_content"]]); ?>


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

                  	$html = Html::encode($data->file);
                       

                    return $html;
                  }

              ],
              [
                  'attribute' => 'language',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width:15%'],
                  'value'=>function($data){

                  	$html = Html::encode($data->file);
                       

                    return $html;
                  }

              ],
              [
                  'header' => 'Periodo',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width:25%'],
                  'value'=>function($data){

                    $returnhtml = ' ---';

                    if($data->date_init != '0000-00-00 00:00:00')
                    {
                      $init = $data->formatDateBD($data->date_init);
                    
                     $returnhtml = $init.' ';
                    }

                    if( $data->date_update != '0000-00-00 00:00:00')
                	{

                		 $end = $data->formatDateBD($data->date_update);

                		 $returnhtml .= ' atualizado '.$end;

                	}

                     

                    return $returnhtml;
                    },
                    
              ],

               [
                  'attribute' => 'time',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width:10%'],
                  'value'=>function($data){

                  	$html = '<span class="mytime" id="item'.$data->id.'">'.Html::encode($data->time).' min</span> ';
                       

                    return $html;
                  }

              ],

              [
                  'header'=>"Start Time",
                  'format' => 'raw',
                  'value'=>function($data){
                    return "<a href='#' class='btn btn-xs btn-info' data-item='item".$data->id."' data-start='0' data-sendurl='".Url::to(['painel/stop-time','id'=>$data->id, 'projeto'=>$data->aaa_projetos_id])."'><i class='fa fa-play'></i></a>";
                    },
                    
              ],

              [
                'class' => 'yii\grid\CheckboxColumn',
                // usar var keys = $('#grid').yiiGridView('getSelectedRows'); para os iscripts
              ]
          ],
        ]);?>

          <?php Pjax::end(); ?>

				</div>
				<!-- end body -->
			</div>
		
	</div>
	
</div>


<script type="text/javascript">
	
	$(document).ready(function(){

		var interval = null;

		$(document).on('click','[data-start]',function(e){
			e.preventDefault();



			item = 'start'
			btn = $(this);
			start = parseInt(btn.data('start'));
			item = btn.data('item');
			getUrl = btn.data('sendurl');

			if(start == 0)
			{
				btn.removeClass('btn-info');
				btn.addClass('btn-warning');

				btn.html("<i class='fa fa-pause'></i>");

				interval = setInterval(function(){

					startzero = start++;

					

					btn.data('start',startzero);
					
					$('#'+item).text(startzero+' seg');					


				},1000);

				//clearInterval(interval); 

			}else{

				clearInterval(interval); 

				btn.removeClass('btn-warning');
				btn.addClass('btn-info');
				btn.html("<i class='fa fa-play'></i>");

				sendstart = btn.data('start');



				$.ajax({
			        url:getUrl,
			        data:{"item":sendstart},
			        method:'post',
			        datatype:'json',
			        beforeSend:function()
			        {
			          btn.attr('disabled',true);
			        },
			        success:function(data)
			        {
			        	btn.attr('disabled',false);

			        	if(data.success == 1)
			        	{

			        		toastr.options.onHidden = function()
						  {
						    location.reload();
						  }

			        		toastr['success']('Tempo modificado com sucesso!');

			        		


			        	}else{

			        		toastr.options.onHidden = function()
						  {
						    //
						  }

			        		toastr['error']('ocorreu um erro no processo de contagem de tempo');

			        	}


			        	
			        	console.log(data.msg);
			         
			        	



			        },
			        


			      });


			}





		});
	});
</script>