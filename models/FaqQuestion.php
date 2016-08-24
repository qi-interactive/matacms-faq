<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\faq\models;

use Yii;
use matacms\db\ActiveQuery;
use mata\media\models\Media;

/**
 * This is the model class for table "matacms_faq_question".
 *
 * @property integer $Id
 * @property string $Question
 * @property string $Answer
 * @property integer $SubjectId
 */
class FaqQuestion extends \matacms\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%matacms_faq_question}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => \mata\arhistory\behaviors\HistoryBehavior::className()
            ],
            [
                'class' => \mata\behaviors\ItemOrderableBehavior::className()
            ]
        ];
    }

    public function rules()
    {
        return [
            [['Question', 'Answer', 'SubjectId'], 'required'],
            [['Question', 'Answer'], 'string'],
        ];
    }

    public static function find() {
        return new FaqQuestionQuery(get_called_class());
    }

    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Question' => 'Question',
            'Answer' => 'Answer',
            'SubjectId' => 'Subject'
        ];
    }

    public function filterableAttributes() {
        return ["Question"];
    }

    public function getLabel() {
        return $this->Question;
    }

    // public function getSubject()
    // {
    //     $city = CategoryItem::find()->with("category")->where(["DocumentId" => $this->getDocumentId()])->one();
    //     return $city != null ? $city->category->Name : null;
    // }

}

class FaqQuestionQuery extends ActiveQuery
{

    public function init() {
        parent::init();
    }

    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge([
            [
                'class' => \mata\behaviors\ItemOrderableBehavior::className()
            ],
        ], parent::behaviors());
    }

}
