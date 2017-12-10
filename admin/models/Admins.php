<?php

namespace app\_adm\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\_adm\components\helpers\ModelHelper;

/**
 * Model que gerencia todos os usuários administrativos
 *
 * @property integer $id
 * @property integer $grupos_id
 * @property string $nome
 * @property string $email
 * @property string $senha
 * @property string $cache_senha
 * @property string $avatar
 * @property integer $status_acesso
 * @property string $parametros_extra
 * @property string $dt_cadastro
 * @property string $dt_ult_acesso
 * @property CsdmAdmHashAcess[] $csdmAdmHashAcesses
 * @property CsdmAdmGrupos $grupos
 * @author André Luiz Pereira <andre@next4.com.br>
 */
 //\yii\db\ActiveRecord
class Admins extends  ModelHelper  implements \yii\web\IdentityInterface
{

  public $AuthKey;
  public $cache_senha;
  public $redefinir_senha = '';

  public $list_grupo_view = ['admin'=>'Administradores','colaboradores'=>'Colaboradores'];

  const CRIAR = 'criar';
  const EDITAR = 'editar';
  const SEARCH = 'search';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

     /**
     * Controlo todos so scenarios
     * @author André Luiz Pereira <andre@next4.com.br>
     * @return array - retorna os scenarios formatados
     */
    public function getCustomScenarios()
    {

      //[['adm_grupos_id', 'grupos_view', 'nome', 'email', 'senha', 'avatar', 'status_acesso', 'dt_cadastro', 'dt_ult_acesso', 'parametros_extra'], 'required'],
      return [
          self::CRIAR      =>  ['usuario','senha','redefinir_senha'],
          self::EDITAR     =>  ['usuario','senha','redefinir_senha'],
          self::SEARCH     =>  ['usuario','senha','redefinir_senha']
      ];

    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
      $scenarios = $this->getCustomScenarios();
      return $scenarios;
    }

    /**
     * Trata campos que não serão validados com requiridos
     * @author André Luiz Pereira <andre@next4.com.br>
     * @return array - retorna os scenarios formatados
     */
    public function TratarRequired()
    {

      $allscenarios = $this->getCustomScenarios();
      
      return $allscenarios;

    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        $allscenarios = $this->TratarRequired();

        return [
            [$allscenarios[self::CRIAR], 'required', 'on' => self::CRIAR],
            [$allscenarios[self::EDITAR], 'required', 'on' => self::CRIAR],
            [$allscenarios[self::SEARCH], 'required', 'on' => self::SEARCH],
            [['usuario'], 'string'],
            [['senha'], 'string', 'max' => 100],
            ['senha','string','min'=>8,'message'=>\Yii::t('admin.administradores', "A senha administrativa deve ter no mínimo 8 caracteres")],
            ['redefinir_senha', 'compare', 'compareAttribute'=>'senha', 'message'=>\Yii::t('admin.administradores', "Este campo deve ser idêntico ao campo de senha") ],
            
        ];
    }


    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {

        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
            if($this->scenario == self::CRIAR)
            {
                $hash = Yii::$app->getSecurity()->generatePasswordHash($this->senha);
                $this->senha = $hash;
            } 


            if($this->scenario == self::EDITAR)
            {
                if(!empty($this->senha))
                {
                    $hash = Yii::$app->getSecurity()->generatePasswordHash($this->senha);
                    $this->senha = $hash;    
                }else{
                    $this->senha = $this->cache_senha;    
                }
                
            } 



            return parent::afterValidate();  
    }

     /**
     * @inheritdoc
     */
    public function afterFind()
    {
            $this->cache_senha = $this->senha;
            $this->senha = '';

        return parent::afterFind();
    }

     /**
     * @inheritdoc
     */
     public function attributeLabels()
        {
            return [
                 'id' => 'id',
                'usuario' =>'Usuario',
                'senha' => 'senha',
                'redefinir_senha'=>'Redefinir senha',
            ];
        }

   
    /**
     * lista sómente para o grupo_view de admins de visualização
     * @author André Luiz Pereira <andre@next4.com.br>
     * @param array $params - GET convertido em array para consulta 
     * @return yii\data\ActiveDataProvider
     */
    public function search($params)
    {

        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
              'and',
                ['like','usuario',$this->usuario],
                

            ]
                        );

        return $dataProvider;
    }

    

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //password = senha do usuário
        // $this->senha é sempre limpa na consulta, deixo o hash da senha cache_senha
        return Yii::$app->getSecurity()->validatePassword($password, $this->cache_senha);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
                ->where(['usuario' =>$username])->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->AuthKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
       return $this->getAuthKey() === $authKey;
    }

    
}
