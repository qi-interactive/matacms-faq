<?php

namespace matacms\faq\clients;

use matacms\faq\models\FaqSubject;

class FaqClient extends \matacms\clients\SimpleClient {

    protected $closureParams = [];

    public function findAll() {

		$model = $this->getModel();
		$this->closureParams = [$model];

		$model = $model::getDb()->cache(function ($db) {
			$closureParams = $this->getClosureParams();
		    return $closureParams[0]->find()->ordered()->with('questions')->all();
		}, null, new \matacms\cache\caching\MataLastUpdatedTimestampDependency());

		return $model;
	}

	public function getModel() {
		return new FaqSubject();
	}

}
