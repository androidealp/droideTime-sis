<?php
namespace app\components;
use yii\web\Controller;
use yii\filters\AccessControl;

class ControllerHelper extends Controller
{

 


    public function behaviors()
    {
    

        \Yii::$app->view->params['title-page'] = 'Painel de controle';

        \Yii::$app->view->params['breadcrumbs-links'] =[
            [
            'label'=>'Gerenciar painel',
            ]
        ];
        

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
                        'actions' => ['ajax-login'],
                        'roles' => ['?'],
                    ],
                ],
            ]
        ];
    } // end function




}
