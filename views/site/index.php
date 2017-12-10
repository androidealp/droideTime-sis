<?php
    
    use \yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'DroideTime';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Seja Bem-Vindo!</h1>

        <p class="lead">Este é nosso sistema online para controle de tempo de projeto</p>

        <p>
            <a title="Fazer login" data-modalsize="sm" data-pajaxid='false' class="btn btn-lg btn-success" data-formid="form-login" data-btaddurl="<?=Url::to(['painel/ajax-login']);?>"><i class="fa fa-user" aria-hidden="true"></i> Acesse seu painel</a>
        </p>
    </div>

</div>
