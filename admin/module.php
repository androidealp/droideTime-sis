<?php

namespace app\_adm;
use \Yii;


class Modules extends \yii\base\Module
{
    public $controllerNamespace = 'app\admin\controllers';

    public function init()
    {
      $this->defaultRoute = 'painel';

      parent::init();
      //\Yii::configure($this, require(__DIR__ . '/config.php'));
      

      \Yii::$app->errorHandler->errorAction = 'admin/painel/error';
     
       
        \Yii::$app->setComponents(
        [
           
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => 'app\admin\models\Admin',
                'enableAutoLogin' => false,
                'authTimeout' => 360*60,
                'loginUrl' => Yii::$app->urlManager->createUrl(['admin/painel/login']),
                'identityCookie' => [
                      'name' => '_adminUser', // unique for backend
                      //'path' => '/web' // correct path for backend app.
                  ]
            ],
            'session' => [
                'class' => 'yii\web\Session',
                'timeout'=>360*60,
                'name' => 'adminSessao',
                'savePath' =>  __DIR__ . '/../admin/sessions',
            ],
        ]
    );

         Yii::$app->assetManager->bundles = [
            'yii\web\JqueryAsset' => [
                'jsOptions' => [ 'position' => \yii\web\View::POS_HEAD ],
            ],
        ];


         

        
    }
}
