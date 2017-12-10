<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%files_projetos}}".
 *
 * @property integer $id
 * @property string $file
 * @property string $language
 * @property integer $time
 * @property string $date_init
 * @property string $date_update
 * @property integer $aaa_projetos_id
 * @property integer $aaa_projetos_users_id
 *
 * @property Projetos $aaaProjetos
 */
class FilesProjetos extends \yii\db\ActiveRecord
{

     const CRIAR = 'criar';
  const EDITAR = 'editar';
  const SEARCH = 'search';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%files_projetos}}';
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
          self::CRIAR      =>  ['file', 'language','aaa_projetos_id', 'aaa_projetos_users_id', 'date_init'],
          self::EDITAR     =>  ['date_update', 'time'],
          self::SEARCH     =>  ['file', 'language', 'time', 'date_init', 'date_update', 'aaa_projetos_id', 'aaa_projetos_users_id'],
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
            [['time', 'aaa_projetos_id', 'aaa_projetos_users_id'], 'integer'],
            [['date_init', 'date_update'], 'safe'],
            [['file', 'language'], 'string', 'max' => 45],
            [['aaa_projetos_id', 'aaa_projetos_users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projetos::className(), 'targetAttribute' => ['aaa_projetos_id' => 'id', 'aaa_projetos_users_id' => 'users_id']],
        ];
    }



    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if($this->scenario == 'criar')
        {
            $this->aaa_projetos_users_id =     \Yii::$app->user->identity->id;
            

            $explod = explode('/', $this->date_init);
            

            if(isset($explod[0]) && isset($explod[1]) && isset($explod[2]))
            {
                $this->date_init   = date($explod[2].'-'.$explod[1].'-'.$explod[0].' H:i:s');    
            }

            
        }


        if($this->scenario == 'editar')
        {
            $this->date_update = date('Y-m-d H:i:s');
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
            'file' => 'File',
            'language' => 'Language',
            'time' => 'Time',
            'date_init' => 'Date Init',
            'date_update' => 'Date Update',
            'aaa_projetos_id' => 'Aaa Projetos ID',
            'aaa_projetos_users_id' => 'Aaa Projetos Users ID',
        ];
    }


      /**
     * lista todos os itens de proejeto do usuário
     * @author André Luiz Pereira <andre@next4.com.br>
     * @param array $params - GET convertido em array para consulta 
     * @return yii\data\ActiveDataProvider
     */
    public function search($projeto_id, $params)
    {

        $id =     \Yii::$app->user->identity->id;
        
        $query = self::find()->where(['aaa_projetos_users_id'=>$id,'aaa_projetos_id'=>$projeto_id])->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
              'and',
                ['like','file',$this->file],
                ['like','language',$this->language],
                ['time',$this->time],
                

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
    public function getAaaProjetos()
    {
        return $this->hasOne(Projetos::className(), ['id' => 'aaa_projetos_id', 'users_id' => 'aaa_projetos_users_id']);
    }
}
