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
* This is the model class for table "faq_subject".
*
* @property integer $Id
* @property string $Subject
* @property string $Language
*/
class FaqSubject extends \matacms\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%matacms_faq_subject}}';
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
            [['Subject'], 'required'],
            [['Subject'], 'string'],
        ];
    }

    public static function find() {
        return new FaqSubjectQuery(get_called_class());
    }

    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Subject' => 'Subject',
        ];
    }

    public function filterableAttributes() {
        return ["Subject"];
    }

    public function getLabel() {
        return $this->Subject;
    }

    /**
    * @return \matacms\db\ActiveQuery
    */
    public function getQuestions()
    {
        return $this->hasManyOrdered(\matacms\faq\models\FaqQuestion::className(), ['SubjectId' => 'Id']);
    }

    public function hasManyOrdered($class, $link)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        $query = $class::find()->ordered();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = true;
        return $query;
    }

}

class FaqSubjectQuery extends ActiveQuery
{

    public function init() {
        parent::init();
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

}
