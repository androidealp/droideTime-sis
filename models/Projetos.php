<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%projetos}}".
 *
 * @property integer $id
 * @property integer $users_id
 * @property string $nome
 * @property integer $time_total
 * @property string $date_init
 * @property string $date_end
 *
 * @property FilesProjetos[] $filesProjetos
 * @property Users $users
 */
class Projetos extends \yii\db\ActiveRecord
{

     const CRIAR = 'criar';
  const EDITAR = 'editar';
  const SEARCH = 'search';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%projetos}}';
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
          self::CRIAR      =>  ['users_id', 'nome'],
          self::EDITAR     =>  ['time_total', 'date_end'],
          self::SEARCH     =>  ['users_id', 'nome', 'time_total', 'date_init'],
      ];

    }

     /**
     * Trata campos que não serão validados com requiridos
     * @author André Luiz Pereira <andre@next4.com.br>
     * @return array - retorna os scenarios formatados
     */
    public function TratarRequired()
    {

      $allscenarios = $this->getCustomScenarios();
     //$allscenarios[self::CRIAR] = array_diff($allscenarios[self::CRIAR], ['parametros_extras']);
     
      return $allscenarios;

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
     * @inheritdoc
     */
    public function rules()
    {

        $allscenarios = $this->TratarRequired();

        return [
             [$allscenarios[self::CRIAR], 'required', 'on' => self::CRIAR],
            [$allscenarios[self::EDITAR], 'required', 'on' => self::EDITAR],
            [$allscenarios[self::SEARCH], 'required', 'on' => self::SEARCH],
            [['users_id', 'time_total'], 'integer'],
            [['date_init', 'date_end'], 'safe'],
            [['nome'], 'string', 'max' => 45],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }


    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        
        if($this->scenario == 'editar')
        {
            $this->date_end = date('Y-m-d H:i:s');
        }

        return parent::beforeValidate();
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => 'Users ID',
            'nome' => 'Nome',
            'time_total' => 'Periodo',
            'date_init' => 'Date Init',
            'date_end' => 'Date End',
        ];
    }


   /**
     * lista todos os projetos do usuário
     * @author André Luiz Pereira <andre@next4.com.br>
     * @param array $params - GET convertido em array para consulta 
     * @return yii\data\ActiveDataProvider
     */
    public function search($params)
    {

        $id =     \Yii::$app->user->identity->id;
        
        $query = self::find()->where(['users_id'=>$id])->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
              'and',
                ['like','nome',$this->nome],
                

            ]
                        );

        return $dataProvider;
    }


    /**
 * Formata a data vinda do bd
 * @author André Luiz Pereira <andre@next4.com.br>
 * @param string $date - Data do banco que precisa ser formatada
 * @param string $format - Formado da data utilizando php, padrão d/m/Y de Y-m-d H:i:s
 * @return string - data formatada
 */
public function formatDateBD($date, $format = 'd/m/Y')
{
    $phpdate = strtotime( $date );

    $dateFormat = date($format, $phpdate);

    return $dateFormat;
}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilesProjetos()
    {
        return $this->hasMany(FilesProjetos::className(), ['aaa_projetos_id' => 'id', 'aaa_projetos_users_id' => 'users_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
}
