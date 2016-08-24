<?php

/**
* @link http://www.matacms.com/
* @copyright Copyright (c) 2015 Qi Interactive Limited
* @license http://www.matacms.com/license/
*/

namespace matacms\faq\controllers;

use Yii;
use matacms\faq\models\FaqQuestion;
use matacms\faq\models\FaqQuestionSearch;
use matacms\faq\models\FaqSubject;
use matacms\controllers\module\Controller;
use matacms\base\MessageEvent;
use yii\data\ActiveDataProvider;
use yii\web\View;
use mata\helpers\BehaviorHelper;
use yii\web\NotFoundHttpException;
use yii\data\Sort;

class FaqController extends Controller {

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
		return \yii\helpers\Url::to(['rearrangeable', 'SubjectId' => Yii::$app->request->get('SubjectId')]);
	}

	public function actionRearrangeable() {

		$query = FaqQuestion::find()->ordered()->where(['SubjectId' => Yii::$app->request->get('SubjectId')]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 2000,
			],
		]);

		$dataProvider->pagination = false;
		$rearrangeActionUrl = $this->actions()['rearrange']['url'];

		return $this->renderAjax('@matacms/modules/faq/views/faq/_rearrangeable', ['dataProvider' => $dataProvider, 'rearrangeActionUrl' => $rearrangeActionUrl]);
	}

	public function getModel() {
		return new FaqQuestion();
	}

	public function getSearchModel() {
		return new FaqQuestionSearch();
	}


	public function actionIndex() {

		$subject = $this->findSubject(Yii::$app->request->get('SubjectId'));

		$searchModel = $this->getSearchModel();
		$searchModel = new $searchModel();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->request->get('SubjectId'));

		$sort = new Sort([
			'attributes' => $searchModel->filterableAttributes()
		]);

		if(!empty($sort->orders)) {
			$dataProvider->query->orderBy = null;
		} else {
			if(BehaviorHelper::hasBehavior($searchModel, \mata\arhistory\behaviors\HistoryBehavior::class)) {
				$dataProvider->query->select('faq_question.*');
				$reflection =  new \ReflectionClass($searchModel);
				$parentClass = $reflection->getParentClass();

				$alias = $searchModel->getTableSchema()->name;
				$pk = $searchModel->getTableSchema()->primaryKey;


				if (is_array($pk)) {
					if(count($pk) > 1)
					throw new NotFoundHttpException('Combined primary keys are not supported.');
					$pk = $pk[0];
				}

				$aliasWithPk = $alias . '.' . $pk;

				$dataProvider->query->join('INNER JOIN', 'arhistory_revision', 'arhistory_revision.DocumentId = CONCAT(:class, '.$aliasWithPk.')', [':class' => $parentClass->name . '-']);
				$dataProvider->query->andWhere('arhistory_revision.Revision = (SELECT MAX(Revision) FROM `arhistory_revision` WHERE arhistory_revision.`DocumentId` = CONCAT(:class, '.$aliasWithPk.'))', [':class' => $parentClass->name . '-']);
				$dataProvider->query->orderBy('arhistory_revision.DateCreated DESC');
			}
		}

		$dataProvider->setSort($sort);

		$showRearrange = Yii::$app->request->get('showRearrange', false);

		if($showRearrange == true) {
			\Yii::$app->view->registerJs("$('.rearrangeable-trigger-btn').trigger('click');", View::POS_READY);
		}

		return $this->render("index", [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'sort' => $sort,
			'subject' => $subject
		]);
	}

	public function actionCreate() {

		$subject = $this->findSubject(Yii::$app->request->get('SubjectId'));

		$model = $this->getModel();

		if($subject != null) {			
			$model->SubjectId = $subject->Id;
		}

		$postData = $_POST;

		if(!empty($postData)) {
			if(isset($postData['FaqQuestion']['SubjectId'])) {

				$subject = FaqSubject::find()->where(['Id' => $postData['FaqQuestion']['SubjectId']])->one();

				if($subject == null) {

					$subject = new FaqSubject();
					$subject->Subject = $postData['FaqQuestion']['SubjectId'];

					if(!$subject->save())
					throw new \yii\web\ServerErrorHttpException($subject->getTopError());

					$postData['FaqQuestion']['SubjectId'] = $subject->Id;
				}
			}
			else {
				$postData['FaqQuestion']['SubjectId'] = null;
			}
		}

		if ($model->load($postData) && $model->save()) {
			$this->trigger(self::EVENT_MODEL_CREATED, new MessageEvent($model));

			return $this->redirect(['index', 'SubjectId' => Yii::$app->request->get('SubjectId'), reset($model->getTableSchema()->primaryKey) => $model->getPrimaryKey()]);
		} else {
			return $this->render("create", [
				'model' => $model,
				'subject' => $subject
			]);
		}
	}

	public function actionUpdate($id) {

		$subject = $this->findSubject(Yii::$app->request->get('SubjectId'));

		$model = $this->findModel($id);
		$postData = $_POST;

		if(!empty($postData)) {
			if(isset($postData['FaqQuestion']['SubjectId'])) {

				$subject = FaqSubject::find()->where(['Id' => $postData['FaqQuestion']['SubjectId']])->one();

				if($subject == null) {

					$subject = new FaqSubject();
					$subject->Subject = $postData['FaqQuestion']['SubjectId'];

					if(!$subject->save())
					throw new \yii\web\ServerErrorHttpException($subject->getTopError());

					$postData['FaqQuestion']['SubjectId'] = $subject->Id;
				}
			}
			else {
				$postData['FaqQuestion']['SubjectId'] = null;
			}
		}

		if ($model->load($postData) && $model->save()) {
			$this->trigger(self::EVENT_MODEL_UPDATED, new MessageEvent($model));
			return $this->redirect(['index', reset($model->getTableSchema()->primaryKey) => $model->getPrimaryKey()]);
		} else {

			return $this->render("update", [
				'model' => $model,
				'subject' => $subject
			]);
		}
	}

	protected function findSubject($pk) {

		if($pk == null)
		return null;

		$model = new FaqSubject;
		if (($model = $model::findOne($pk)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}
