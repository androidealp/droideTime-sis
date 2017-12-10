
<?php 
use yii\helpers\Url;
 ?>
<ul class="list-group">
  <li class="list-group-item <?=($active == 'projetos')?'active':''?>">
  	<?php if ($active == 'projetos'): ?>
  		Projetos
  	<?php else: ?>	
  		<a href="<?=Url::to(['projetos'])?>">Projetos</a>
  		
  	<?php endif ?>
  	
  </li>
  <li class="list-group-item <?=($active == 'dados')?'active':''?>">
	  	<?php if ($active == 'dados'): ?>
	  		Meus dados
	  	<?php else: ?>	
	  		<a href="<?=Url::to(['meus-dados'])?>">Meus dados</a>
	  		
	  	<?php endif ?>
  </li>
  
</ul>