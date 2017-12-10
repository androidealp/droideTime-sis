<?php
namespace app\admin\helpers;
use yii\web\Controller;
use yii\filters\AccessControl;
use \app\helpers\LayoutHelper;
use app\admin\helpers\MenuHelper;

class ControllerHelper extends Controller
{

 


    public function behaviors()
    {
      if(!\Yii::$app->user->isGuest)
      {

       

        $permissoes = \app\admin\models\AdmGrupos::find()->select(['permissoes','grupo_view'])
        ->where(['id'=>\Yii::$app->user->identity->adm_grupos_id])
        ->one();
        \Yii::$app->view->params['permissoes'] = $permissoes->permissoes;
        \Yii::$app->view->params['grupo_view'] = $permissoes->grupo_view;
        \Yii::$app->view->params['main_menu'] = MenuHelper::AdmMenu()->ListMenu();
      }else{
        \Yii::$app->view->params['permissoes'] = [];
        \Yii::$app->view->params['grupo_view'] = [];
      }


         
         
        \Yii::$app->view->params['title-page'] = 'Painel de controle';

        \Yii::$app->view->params['breadcrumbs-links'] =[
            [
            'label'=>'Gerenciar painel',
            ]
        ];
        
        $layout = new LayoutHelper;

        $this->layout =  $layout->loadThemesJson()->admin();

        return [
            'access' => [
                'class' => AccessControl::className(),

                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login','validar-email-adm','captcha'],
                        'roles' => ['?'],
                    ],
                ],
            ]
        ];
    } // end function




}
