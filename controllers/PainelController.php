<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\components\ControllerHelper;

class PainelController extends ControllerHelper
{


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionAjaxLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['projetos']);
        }

        if (!\Yii::$app->request->isAjax) {
                
              throw new \yii\web\HttpException(404, 'Página não existe');
            
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {

            if($model->login())
            {
                return $this->redirect(['projetos']);    
            }else{
                 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                 return [
                    'type'=>'error',
                    'msg'=>'Usuário ou senha não conferem',
                 ];
            }
            
        }

            return $this->renderAjax('ajax-login',[
              'model'=>$model
              ]);

        
    }



     public function actionProjetos()
    {

        $model = new \app\models\Projetos;
        $model->scenario = 'search';

        $dataprovider = $model->search(\Yii::$app->request->queryParams);

        return $this->render('projetos',[
            'dataprovider'=>$dataprovider,
            'model'=>$model
        ]);
    }

    public function actionCriarProjeto()
    {

        if(!\Yii::$app->request->isAjax)
        
        {                                                                                                                  
            throw new \yii\web\HttpException(403, 'Você não tem permissão para acessar esta página');
        } 

        $model = new \app\models\Projetos;
        $model->scenario = 'criar';

        $model->users_id = \Yii::$app->user->identity->id;



        if ($model->load(\Yii::$app->request->post()))
        { 

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         
            if($model->save())
            {

                return [
                     'msg'=>'Projeto '.$model->nome.' criado com sucesso',
                     'type'=>'success'
                ];

            }else{

                return [
                     'msg'=>'foram encontradors alguns erros no processo de criacao, tente novamente mais tarde',
                     'type'=>'error'
                ];
            }

        }

        return $this->renderAjax('criar-projeto',[
                  'model'=>$model
        ]);

    }

    public function actionDeletarProjeto()
    {


         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

         $post = \Yii::$app->request->post('del-list',['sem']);

          $projeto = \app\models\Projetos::find()->where(['users_id'=> \Yii::$app->user->identity->id])->andWhere(['in','id',$post])->count();

        if(!$projeto)
        {
            return [
                    'msg'=>'Erro ao tentar deletar o registro, você não é dono deste projeto ou ele não existe.',
                    'type'=>'error'
                ];
        }


        $model = new \app\models\Projetos;
        $return = ['msg'=>'É necessário selecionar uma linha para poder deletar!',
                  'type'=>'error'
                  ];

        if(\Yii::$app->request->post('del-list')){

          $post = \Yii::$app->request->post('del-list');
          $total =count($post);
          if($model->deleteAll(['id'=>$post])){

                $msg = 'Foram deletados um total de '.$total.' registro(s)';

                $return = [
                  'msg'=>$msg,
                  'type'=>'success'
                  ];
            }else{
              $return = [
                    'msg'=>'Erro ao tentar deletar o registro.',
                    'type'=>'error'
                ];
            }
        }

      return $return;

    }

    public function actionDetalhes($id)
    {

        $model = new \app\models\FilesProjetos;
        $model->scenario = 'search';

        $projeto = \app\models\Projetos::findOne($id);

        if(!$projeto)
        {

            throw new \yii\web\HttpException(404, 'página não existe');
            
        }

        $dataprovider = $model->search($id,\Yii::$app->request->queryParams);

        return $this->render('detalhes',[
            'dataprovider'=>$dataprovider,
            'model'=>$model,
            'projeto'=>$projeto
        ]);

        
    }


    public function actionCriarItem($id_proj)
    {

        $projeto = \app\models\Projetos::findOne($id_proj);

        if(!$projeto)
        {

            throw new \yii\web\HttpException(404, 'página não existe');
            
        }

         if(!\Yii::$app->request->isAjax)
        
        {                                                                                                                  
            throw new \yii\web\HttpException(403, 'Você não tem permissão para acessar esta página');
        } 

        $model = new \app\models\FilesProjetos;
        $model->scenario = 'criar';

        $model->aaa_projetos_id = $projeto->id;



        if ($model->load(\Yii::$app->request->post()))
        { 

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         
            if($model->save())
            {

                return [
                     'msg'=>'item '.$model->file.' criado com sucesso',
                     'type'=>'success'
                ];

            }else{

                return [
                     'msg'=>'foram encontradors alguns erros no processo de criacao, tente novamente mais tarde',
                     'type'=>'error'
                ];
            }

        }

        return $this->renderAjax('criar-item',[
                  'model'=>$model
        ]);



    }


    public function actionStopTime($id, $projeto)
    {

         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $projeto = \app\models\Projetos::findOne($projeto);

        $model = \app\models\FilesProjetos::find()->where([
            'id'=>$id,
            'aaa_projetos_id'=>$projeto,
            'aaa_projetos_users_id'=>    \Yii::$app->user->identity->id
            
        ])->one();

        $segundos =(int) \Yii::$app->request->post('item',0);

        if(!$model || !$segundos || !$projeto)
        {

            return ['error'=>1,'success'=>0, 'msg'=>'não localizou registros e os segundos retornaram: '.$segundos];
            
        }

        $projeto->scenario = 'editar';

        $model->scenario = 'editar';

        $tempoemminutos = round($model->time +( $segundos/60));

        $model->time = $tempoemminutos;

        $projeto->time_total = round($projeto->time_total + ( $segundos/60));

        if($model->save() && $projeto->save())
            {



                return ['error'=>0,'success'=>1, 'msg'=>'salvo com sucesso'];
            }else{
                return ['error'=>1,'success'=>0, 'msg'=>'não salvou por conta:'.print_r($model->getErrors(),true)];
            }


    }

    public function actionDeletarItem($id_proj)
    {


         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

         $post = \Yii::$app->request->post('del-list',['sem']);

        $filesprojeto = \app\models\FilesProjetos::find()->where(['aaa_projetos_id'=>$id_proj,'aaa_projetos_users_id'=> \Yii::$app->user->identity->id])->andWhere(['in','id',$post])->count();

        if(!$filesprojeto)
        {
            return [
                    'msg'=>'Erro ao tentar deletar o registro, você não é dono deste projeto ou ele não existe.',
                    'type'=>'error'
                ];
        }


        $model = new \app\models\FilesProjetos;
        $return = ['msg'=>'É necessário selecionar uma linha para poder deletar!',
                  'type'=>'error'
                  ];

        if(\Yii::$app->request->post('del-list')){

          $post = \Yii::$app->request->post('del-list');
          $total =count($post);
          if($model->deleteAll(['id'=>$post])){

                $msg = 'Foram deletados um total de '.$total.' registro(s)';

                $return = [
                  'msg'=>$msg,
                  'type'=>'success'
                  ];
            }else{
              $return = [
                    'msg'=>'Erro ao tentar deletar o registro.',
                    'type'=>'error'
                ];
            }
        }

    }


    public function actionMeusDados()
    { 
           $session = Yii::$app->session;
        $model = \Yii::$app->user->identity;

        $model->scenario = 'editar';

        if($model->load(    \Yii::$app->request->post()))
            {
             
                $session->setFlash('success', 'Por questão de segurança este recurso foi desativado');
            }
        

        return $this->render('meus-dados',[
            'model'=>$model,
            'session'=>$session
        ]);
    }

        

   
}
