<?php

/**
* @link http://www.matacms.com/
* @copyright Copyright (c) 2015 Qi Interactive Limited
* @license http://www.matacms.com/license/
*/

namespace matacms\faq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use matacms\faq\models\FaqQuestion;


/**
* FaqQuestionSearch represents the model behind the search form about `matacms\faq\models\FaqQuestion`.
*/
class FaqQuestionSearch extends FaqQuestion {

    public function rules()
    {
        return [
            [['Id'], 'integer'],
            [['Question', 'Answer'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
    * Creates data provider instance with search query applied
    *
    * @param array $params
    *
    * @return ActiveDataProvider
    */
    public function search($params, $subjectId = null)
    {
        $query = FaqQuestion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            ]);

            $this->load($params);

            if($subjectId != null)
            $this->SubjectId = $subjectId;

            if (!$this->validate()) {
                // uncomment the following line if you do not want to any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }

            $query->andFilterWhere([
                'Id' => $this->Id,
                ]);

                $query->andFilterWhere(['like', 'Question', $this->Question])
                ->andFilterWhere(['like', 'Answer', $this->Answer])
                ->andFilterWhere(['SubjectId' => $this->SubjectId]);

                return $dataProvider;
            }

        }
