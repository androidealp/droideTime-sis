<?php

namespace app\models;


/**
 * Model que gerencia todos os usuários administrativos
 *
 * @property integer $id
 * @property string $senha
 * @property string $cache_senha

 * @author André Luiz Pereira <andre@next4.com.br>
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
 
  const CRIAR = 'criar';
  const EDITAR = 'editar';
  const SEARCH = 'search';
   public $AuthKey;
  public $redefinir_senha = '';
  public $cache_senha;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
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
      //$allscenarios[self::CRIAR] = array_diff($allscenarios[self::CRIAR], ['avatar']);
      
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
            ['senha','string','min'=>8,'message'=>"A senha deve ter no mínimo 8 caracteres"],
            ['redefinir_senha', 'compare', 'compareAttribute'=>'senha', 'message'=>'O campo redefinir senha de ser identico a senha' ],
            
        ];
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
     * lista sómente para os usuários em geral
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
        return \Yii::$app->getSecurity()->validatePassword($password, $this->cache_senha);
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
