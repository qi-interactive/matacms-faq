<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2016 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\faq;

use mata\base\Module as BaseModule;
use matacms\faq\models\FaqSubject;
use matacms\faq\models\FaqSubjectSearch;

class Module extends BaseModule {

	public function getNavigation() {

		$subjectsModel = new FaqSubjectSearch;

		$subjects = $subjectsModel->searchAllForNav()->all();

        $navigation = [];

        foreach($subjects as $subject) {
			$navigation[] = [
				'label' => $subject->Subject,
				'url' => "/mata-cms/faq/faq?SubjectId=" . $subject->Id,
				'icon' => "/images/module-icon.svg"
			];
        }

		$navigation[] = [
			'label' => "Rearrange",
			'url' => "/mata-cms/faq/faq-subject?showRearrange=true",
			'icon' => "/images/rearrange.svg"
		];

		$navigation[] = [
			'label' => "Add Subject",
			'url' => "/mata-cms/faq/faq-subject/create",
			'icon' => "/images/plus.svg"
		];

		$navigation[] = [
			'label' => "Add Question",
			'url' => "/mata-cms/faq/faq/create",
			'icon' => "/images/plus.svg"
		];

		return $navigation;

	}

}
