<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\faq\controllers;

use Yii;
use matacms\faq\models\FaqSubject;
use matacms\faq\models\FaqSubjectSearch;
use matacms\controllers\module\Controller;
use matacms\base\MessageEvent;
use yii\data\ActiveDataProvider;
use yii\web\View;

class FaqSubjectController extends Controller {

	public function actions()
    {

        return array_merge([
            'rearrange' => [
                'class' => 'mata\actions\RearrangeAction',
                'model' => $this->getModel(),
                'url' => \yii\helpers\Url::to(['rearrange']),
                'orderColumnName' => 'Order',
                'onValidationErrorHandler' => function() { echo 'ERROR'; },
            ],
        ], parent::actions());
    }

	public function getRearrangeableUrl()
    {
    	return \yii\helpers\Url::to(['rearrangeable']);
    }

    public function actionRearrangeable() {

		$query = FaqSubject::find()->ordered();

    	$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 2000,
			],
        ]);

    	$dataProvider->pagination = false;
    	$rearrangeActionUrl = $this->actions()['rearrange']['url'];

    	return $this->renderAjax('@vendor/matacms/matacms-base/views/module/_rearrangeable', ['dataProvider' => $dataProvider, 'rearrangeActionUrl' => $rearrangeActionUrl]);
    }

	public function getModel() {
		return new FaqSubject();
	}

	public function getSearchModel() {
		return new FaqSubjectSearch();
	}

	public function actionDelete($id) {
		throw new \yii\web\NotFoundHttpException('You cannot delete FAQ subject');
	}

}
